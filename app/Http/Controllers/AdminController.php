<?php

namespace App\Http\Controllers;

use App\Services\DonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use DateTimeImmutable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private const DEFAULT_SECTION = 'overview';

    private function sb(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withHeaders([
            'apikey'        => config('services.supabase.service_role_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_role_key'),
            'Content-Type'  => 'application/json',
        ]);
    }

    private function sbUrl(string $table, array $query = []): string
    {
        $url = rtrim(config('services.supabase.url'), '/') . '/rest/v1/' . $table;
        if ($query) $url .= '?' . http_build_query($query);
        return $url;
    }

    private function sanitizeSection(?string $section): string
    {
        $allowed = ['overview', 'reservations', 'schedule', 'announcements', 'customers', 'reports', 'donations'];
        if ($section === null) return self::DEFAULT_SECTION;
        $normalized = strtolower(trim($section));
        return in_array($normalized, $allowed) ? $normalized : self::DEFAULT_SECTION;
    }

    private function isLoggedIn(): bool
    {
        return Session::get('admin_logged_in') === true;
    }

    private function isAdmin(): bool
    {
        return Session::get('admin_role') === 'admin';
    }

    private function isSecretary(): bool
    {
        return Session::get('admin_role') === 'secretary';
    }

    private function secretaryAllowedSections(): array
    {
        return ['reservations', 'schedule', 'donations', 'customers'];
    }

    private function validateLogin(string $username, string $password): ?string
    {
        if ($username === config('services.admin.username') && $password === config('services.admin.password')) {
            return 'admin';
        }
        if ($username === config('services.secretary.username') && $password === config('services.secretary.password')) {
            return 'secretary';
        }
        return null;
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('admin.index')->with('logout_success', 'You have been logged out successfully.');
    }

    public function handle(Request $request)
    {
        $action          = $request->input('action');
        $redirectSection = $this->sanitizeSection($request->input('redirect_section'));

        // LOGIN
        if ($action === 'login') {
            $username = trim($request->input('username', ''));
            $password = $request->input('password', '');
            $role = $this->validateLogin($username, $password);
            if ($role !== null) {
                Session::put('admin_logged_in', true);
                Session::put('admin_username', $username);
                Session::put('admin_role', $role);
                Session::flash('login_success', 'Welcome back!');
                $landing = $role === 'secretary' ? 'reservations' : $redirectSection;
                return redirect()->route('admin.index', ['section' => $landing]);
            }
            return back()->with('login_error', 'Invalid username or password.');
        }

        if (!$this->isLoggedIn()) {
            return redirect()->route('admin.index')->with('flash_error', 'You must be logged in.');
        }

        // UPDATE STATUS
        if ($action === 'update_status') {
            $id     = (int) $request->input('reservation_id');
            $status = $request->input('status');
            try {
                $resResponse = $this->sb()->get($this->sbUrl('reservations', ['id' => 'eq.' . $id, 'select' => '*', 'limit' => 1]));
                $reservation = $resResponse->successful() ? ($resResponse->json()[0] ?? null) : null;

                if ($reservation) {
                    // Require an officiant before approving
                    if ($status === 'approved' && empty($reservation['officiant_id'])) {
                        Session::flash('status_update_error', 'Please assign a priest before approving this reservation.');
                        return redirect()->route('admin.index', ['section' => 'reservations']);
                    }

                    $note = trim($request->input('admin_note', ''));
                    $adminNote = $note !== ''
                        ? $note
                        : match ($status) {
                            'approved'  => 'Approved by admin',
                            'declined'  => 'Declined by admin',
                            'cancelled' => 'Cancelled by the parish office',
                            default     => 'Marked as pending',
                        };

                    $this->sb()->patch($this->sbUrl('reservations', ['id' => 'eq.' . $id]), [
                        'status'     => $status,
                        'admin_note' => $adminNote,
                        'updated_at' => now()->toISOString(),
                    ]);

                    $details = $reservation['details'] ?? [];
                    if (is_string($details)) $details = json_decode($details, true) ?? [];

                    $phone = $reservation['phone'] ?? ($details['phone'] ?? null);
                    $name  = $reservation['name']  ?? ($details['name']  ?? 'Customer');
                    $email = $reservation['email'] ?? ($details['email'] ?? null);

                    // Fall back to customer table if still no email
                    if (empty($email) && !empty($reservation['customer_id'])) {
                        $custRes = $this->sb()->get($this->sbUrl('customers', [
                            'id'     => 'eq.' . $reservation['customer_id'],
                            'select' => 'email,name',
                            'limit'  => 1,
                        ]));
                        if ($custRes->successful() && !empty($custRes->json()[0]['email'])) {
                            $email = $custRes->json()[0]['email'];
                            $name  = $name ?: ($custRes->json()[0]['name'] ?? 'Customer');
                        }
                    }

                    // Fetch assigned priest name for the email
                    $priestName = '';
                    if (!empty($reservation['officiant_id'])) {
                        $priestRes = $this->sb()->get($this->sbUrl('priests', [
                            'id'     => 'eq.' . $reservation['officiant_id'],
                            'select' => 'name,title',
                            'limit'  => 1,
                        ]));
                        if ($priestRes->successful() && !empty($priestRes->json()[0])) {
                            $p = $priestRes->json()[0];
                            $priestName = trim(($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? ''));
                        }
                    }

                    if (!empty($email)) {
                        try {
                            Mail::send('emails.reservation-status', [
                                'name'             => $name,
                                'status'           => $status,
                                'event_type'       => $reservation['event_type'] ?? '',
                                'reservation_date' => $reservation['preferred_date'] ?? ($reservation['reservation_date'] ?? ''),
                                'reservation_time' => $reservation['reservation_time'] ?? '',
                                'admin_note'       => $adminNote,
                                'priest_name'      => $priestName,
                            ], function ($message) use ($email, $status) {
                                $message->to($email)->subject(match ($status) {
                                    'approved' => 'Your reservation has been approved',
                                    'declined' => 'Update on your reservation request',
                                    'cancelled' => 'Your reservation has been cancelled',
                                    default    => 'Reservation status update',
                                });
                            });
                        } catch (\Throwable $mailEx) {
                            \Illuminate\Support\Facades\Log::error('Reservation status email failed: ' . $mailEx->getMessage());
                            Session::flash('flash_warning', 'Status updated but email notification failed: ' . $mailEx->getMessage());
                        }
                    }

                    if (!empty($phone)) {
                        app(\App\Services\SmsNotificationService::class)->send(
                            $phone,
                            match ($status) {
                                'approved' => "Good news {$name}! Your reservation has been APPROVED.",
                                'declined' => "Hello {$name}, your reservation was DECLINED. Please contact the parish.",
                                'cancelled' => "Hello {$name}, your reservation has been CANCELLED by the parish. Please contact the parish office.",
                                default    => "Hello {$name}, your reservation status is now {$status}.",
                            }
                        );
                    }
                }
                Session::flash('status_update_type', $status);
                Session::flash('status_update_success', match ($status) {
                    'approved' => 'Reservation approved successfully.',
                    'declined' => 'Reservation declined successfully.',
                    'cancelled' => 'Reservation cancelled. The family has been notified.',
                    default    => 'Reservation status updated successfully.',
                });
            } catch (\Throwable $e) {
                Session::flash('status_update_error', $e->getMessage());
            }
            return redirect()->route('admin.index', ['section' => 'reservations']);
        }

        // APPROVE CANCELLATION
        if ($action === 'approve_cancellation') {
            $id        = (int) $request->input('reservation_id');
            $adminNote = trim($request->input('admin_note', 'Your cancellation request has been approved.'));

            $resResp = $this->sb()->get($this->sbUrl('reservations', ['id' => 'eq.' . $id, 'select' => '*', 'limit' => 1]));
            $reservation = $resResp->successful() ? ($resResp->json()[0] ?? null) : null;

            $patch = $this->sb()->patch($this->sbUrl('reservations', ['id' => 'eq.' . $id]), [
                'status'                 => 'cancelled',
                'cancellation_requested' => false,
                'admin_note'             => $adminNote,
                'updated_at'             => now()->toISOString(),
            ]);

            if ($patch->failed()) {
                Session::flash('approve_cancellation_error', 'Failed to approve cancellation: ' . $patch->body());
                return redirect()->route('admin.index', ['section' => 'reservations']);
            }

            if ($reservation) {
                $email = $reservation['email'] ?? null;
                $name  = $reservation['name'] ?? 'Customer';
                if (empty($email) && !empty($reservation['customer_id'])) {
                    $cust = $this->sb()->get($this->sbUrl('customers', ['id' => 'eq.' . $reservation['customer_id'], 'select' => 'email,name', 'limit' => 1]));
                    if ($cust->successful() && !empty($cust->json()[0])) {
                        $email = $cust->json()[0]['email'] ?? null;
                        $name  = $name ?: ($cust->json()[0]['name'] ?? 'Customer');
                    }
                }
                if ($email) {
                    try {
                        Mail::send('emails.reservation-status', [
                            'name'             => $name,
                            'status'           => 'cancelled',
                            'event_type'       => $reservation['event_type'] ?? '',
                            'reservation_date' => $reservation['reservation_date'] ?? '',
                            'reservation_time' => $reservation['reservation_time'] ?? '',
                            'admin_note'       => $adminNote,
                            'priest_name'      => '',
                        ], function ($message) use ($email) {
                            $message->to($email)->subject('Your reservation has been cancelled');
                        });
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Cancellation approval email failed: ' . $e->getMessage());
                    }
                }
            }

            Session::flash('approve_cancellation_success', 'Cancellation approved and customer notified.');
            return redirect()->route('admin.index', ['section' => 'reservations']);
        }

        // DENY CANCELLATION
        if ($action === 'deny_cancellation') {
            $id        = (int) $request->input('reservation_id');
            $adminNote = trim($request->input('admin_note', 'Your cancellation request has been reviewed and denied.'));

            $resResp = $this->sb()->get($this->sbUrl('reservations', ['id' => 'eq.' . $id, 'select' => '*', 'limit' => 1]));
            $reservation = $resResp->successful() ? ($resResp->json()[0] ?? null) : null;

            $patch = $this->sb()->patch($this->sbUrl('reservations', ['id' => 'eq.' . $id]), [
                'cancellation_requested' => false,
                'admin_note'             => $adminNote,
                'updated_at'             => now()->toISOString(),
            ]);

            if ($patch->failed()) {
                Session::flash('flash_error', 'Failed to deny cancellation: ' . $patch->body());
                return redirect()->route('admin.index', ['section' => 'reservations']);
            }

            if ($reservation) {
                $email = $reservation['email'] ?? null;
                $name  = $reservation['name'] ?? 'Customer';
                if (empty($email) && !empty($reservation['customer_id'])) {
                    $cust = $this->sb()->get($this->sbUrl('customers', ['id' => 'eq.' . $reservation['customer_id'], 'select' => 'email,name', 'limit' => 1]));
                    if ($cust->successful() && !empty($cust->json()[0])) {
                        $email = $cust->json()[0]['email'] ?? null;
                        $name  = $name ?: ($cust->json()[0]['name'] ?? 'Customer');
                    }
                }
                if ($email) {
                    try {
                        Mail::send('emails.reservation-status', [
                            'name'             => $name,
                            'status'           => 'cancellation_denied',
                            'event_type'       => $reservation['event_type'] ?? '',
                            'reservation_date' => $reservation['reservation_date'] ?? '',
                            'reservation_time' => $reservation['reservation_time'] ?? '',
                            'admin_note'       => $adminNote,
                            'priest_name'      => '',
                        ], function ($message) use ($email) {
                            $message->to($email)->subject('Update on your cancellation request');
                        });
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Cancellation denial email failed: ' . $e->getMessage());
                    }
                }
            }

            Session::flash('deny_cancellation_success', 'Cancellation request denied and customer notified.');
            return redirect()->route('admin.index', ['section' => 'reservations']);
        }

        // CREATE ANNOUNCEMENT
        if ($action === 'create_announcement') {
            $title    = trim($request->input('announcement_title', ''));
            $body     = trim($request->input('announcement_body', ''));
            $show     = $request->input('announcement_show') === '1' ? 1 : 0;
            $category = trim($request->input('announcement_category', ''));

            if ($title === '' || $body === '') {
                Session::flash('announcement_action_error', 'Please provide both a title and message.');
                return redirect()->route('admin.index', ['section' => 'announcements']);
            }

            $path = null;

            if ($request->hasFile('announcement_image')) {
                $file     = $request->file('announcement_image');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $contents = file_get_contents($file->getRealPath());

                $upload = \Illuminate\Support\Facades\Http::withBody($contents, $file->getMimeType())
                    ->withHeaders([
                        'apikey'        => config('services.supabase.key'),
                        'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    ])->put(rtrim(config('services.supabase.url'), '/') . '/storage/v1/object/announcements/' . $filename);

                if (!$upload->successful()) {
                    Session::flash('announcement_action_error', 'Image upload failed: ' . $upload->body());
                    return redirect()->route('admin.index', ['section' => 'announcements']);
                }

                $path = 'announcements/' . $filename;
            }

            $insert = $this->sb()->post($this->sbUrl('announcements'), [
                'title'        => $title,
                'body'         => $body,
                'image_path'   => $path,
                'show_on_home' => (bool) $show,
                'category'     => $category ?: null,
                'created_at'   => now()->toISOString(),
            ]);
            if ($insert->successful()) {
                Session::flash('announcement_action_success', 'Announcement published successfully.');
            } else {
                Session::flash('announcement_action_error', 'Failed to publish announcement: ' . $insert->body());
            }
            return redirect()->route('admin.index', ['section' => 'announcements']);
        }

        // EDIT ANNOUNCEMENT
        if ($action === 'edit_announcement') {
            $id       = (int) $request->input('announcement_id');
            $title    = trim($request->input('announcement_title', ''));
            $body     = trim($request->input('announcement_body', ''));
            $show     = $request->input('announcement_show') === '1' ? 1 : 0;
            $category = trim($request->input('announcement_category', ''));

            if ($title === '' || $body === '') {
                Session::flash('announcement_action_error', 'Please provide both a title and message.');
                return redirect()->route('admin.index', ['section' => 'announcements']);
            }

            $patch = [
                'title'        => $title,
                'body'         => $body,
                'show_on_home' => (bool) $show,
                'category'     => $category ?: null,
                'updated_at'   => now()->toISOString(),
            ];

            if ($request->hasFile('announcement_image')) {
                $file     = $request->file('announcement_image');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $contents = file_get_contents($file->getRealPath());

                $upload = \Illuminate\Support\Facades\Http::withBody($contents, $file->getMimeType())
                    ->withHeaders([
                        'apikey'        => config('services.supabase.key'),
                        'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    ])->put(rtrim(config('services.supabase.url'), '/') . '/storage/v1/object/announcements/' . $filename);

                if ($upload->successful()) {
                    $patch['image_path'] = 'announcements/' . $filename;
                }
            }

            $upd = $this->sb()->patch($this->sbUrl('announcements', ['id' => 'eq.' . $id]), $patch);
            Session::flash($upd->successful() ? 'announcement_action_success' : 'announcement_action_error',
                $upd->successful() ? 'Announcement updated.' : 'Update failed: ' . $upd->body());
            return redirect()->route('admin.index', ['section' => 'announcements']);
        }

        // TOGGLE ANNOUNCEMENT
        if ($action === 'toggle_announcement') {
            $id   = (int) $request->input('announcement_id');
            $show = $request->input('show_on_home') === '1';
            $upd  = $this->sb()->patch($this->sbUrl('announcements', ['id' => 'eq.' . $id]), ['show_on_home' => $show]);
            Session::flash($upd->successful() ? 'announcement_action_success' : 'announcement_action_error',
                $upd->successful() ? 'Announcement visibility updated.' : 'Update failed: ' . $upd->body());
            return redirect()->route('admin.index', ['section' => 'announcements']);
        }

        // DELETE ANNOUNCEMENT
        if ($action === 'delete_announcement') {
            $id      = (int) $request->input('announcement_id');
            $annRes  = $this->sb()->get($this->sbUrl('announcements', ['id' => 'eq.' . $id, 'select' => 'id,image_path', 'limit' => 1]));
            $ann     = $annRes->successful() ? ($annRes->json()[0] ?? null) : null;
            if ($ann && !empty($ann['image_path'])) {
                $full = storage_path('app/public/' . $ann['image_path']);
                if (file_exists($full)) @unlink($full);
            }
            $del = $this->sb()->delete($this->sbUrl('announcements', ['id' => 'eq.' . $id]));
            Session::flash($del->successful() ? 'announcement_action_success' : 'announcement_action_error',
                $del->successful() ? 'Announcement deleted successfully.' : 'Delete failed: ' . $del->body());
            return redirect()->route('admin.index', ['section' => 'announcements']);
        }

        if ($action === 'toggle_customer_status') {
    $id     = (int) $request->input('customer_id');
    $status = $request->input('status');
    $reason = trim((string) $request->input('disabled_reason', ''));

    $custRes  = $this->sb()->get($this->sbUrl('customers', ['id' => 'eq.' . $id, 'select' => '*', 'limit' => 1]));
    $customer = $custRes->successful() ? ($custRes->json()[0] ?? null) : null;

    if (!$customer) {
        Session::flash('customer_action_error', 'Customer not found.');
        return redirect()->route('admin.index', ['section' => 'customers']);
    }

    $this->sb()->patch($this->sbUrl('customers', ['id' => 'eq.' . $id]), [
        'status'          => $status,
        'account_status'  => $status,
        'disabled_reason' => $status === 'disabled' ? $reason : null,
    ]);

$emailSent = false;

if (empty($customer['email'])) {
    Session::flash('customer_action_error', 'Customer status updated, but no email was sent because this customer has no email address.');
} else {
    try {
       $subject = $status === 'disabled'
    ? 'Your account has been disabled'
    : 'Your account has been re-enabled';

Mail::send('emails.customer-status', [
    'name' => $customer['name'] ?? 'Customer',
    'status' => $status,
    'reason' => $reason ?: 'No reason provided.',
], function ($message) use ($customer, $subject) {
    $message->from(config('mail.from.address'), config('mail.from.name'));
    $message->to($customer['email']);
    $message->subject($subject);
});

        $emailSent = true;
    } catch (\Throwable $e) {
        Session::flash('customer_action_error', 'Customer status updated, but email failed: ' . $e->getMessage());
        \Illuminate\Support\Facades\Log::error('Customer status email failed: ' . $e->getMessage());
    }
}

if ($emailSent) {
    Session::flash('customer_action_success', 'Customer status updated and email sent to ' . $customer['email'] . '.');
} elseif (!session('customer_action_error')) {
    Session::flash('customer_action_success', 'Customer status updated.');
}
    return redirect()->route('admin.index', ['section' => 'customers']);
}

if ($action === 'reset_password') {
    $id = (int) $request->input('customer_id');

    $custRes  = $this->sb()->get($this->sbUrl('customers', ['id' => 'eq.' . $id, 'select' => 'id,name,email', 'limit' => 1]));
    $customer = $custRes->successful() ? ($custRes->json()[0] ?? null) : null;

    if (!$customer || empty($customer['email'])) {
        Session::flash('customer_action_error', 'Customer email not found.');
        return redirect()->route('admin.index', ['section' => 'customers']);
    }

    $this->sb()->delete($this->sbUrl('customer_password_resets', ['customer_id' => 'eq.' . $customer['id']]));

    $plainToken = Str::random(64);
    $tokenHash  = hash('sha256', $plainToken);

    $this->sb()->post($this->sbUrl('customer_password_resets'), [
        'customer_id' => $customer['id'],
        'token_hash'  => $tokenHash,
        'expires_at'  => now()->addHour()->toISOString(),
        'created_at'  => now()->toISOString(),
    ]);

    $resetUrl = route('password.reset', ['token' => $plainToken]);

    Mail::send('emails.password-reset', [
        'name'      => $customer['name'],
        'reset_url' => $resetUrl,
        'by_admin'  => true,
    ], function ($message) use ($customer) {
        $message->to($customer['email'])
            ->subject('Reset your St. John the Baptist Parish password');
    });

    $this->sb()->patch($this->sbUrl('customers', ['id' => 'eq.' . $customer['id']]), [
        'password_reset_required' => true,
        'last_password_reset_at'  => now()->toISOString(),
    ]);

    Session::flash('customer_action_success', 'Password reset email sent.');
    return redirect()->route('admin.index', ['section' => 'customers']);
}

if ($action === 'purge_expired_pending') {
    $today = now()->toDateString();

    $response = $this->sb()->delete($this->sbUrl('reservations', [
        'status'           => 'eq.pending',
        'reservation_date' => 'lt.' . $today,
    ]));

    if ($response->successful()) {
        Session::flash('purge_expired_success', 'Expired pending reservations have been removed.');
    } else {
        Session::flash('purge_expired_error', 'Failed to remove expired reservations: ' . $response->body());
    }

    return redirect()->route('admin.index', ['section' => 'reservations']);
}

if ($action === 'delete_customer') {
    $id = (int) $request->input('customer_id');

    $custRes  = $this->sb()->get($this->sbUrl('customers', ['id' => 'eq.' . $id, 'select' => 'id', 'limit' => 1]));
    $customer = $custRes->successful() ? ($custRes->json()[0] ?? null) : null;

    if (!$customer) {
        Session::flash('customer_action_error', 'Customer not found.');
        return redirect()->route('admin.index', ['section' => 'customers']);
    }

    $resRes          = $this->sb()->get($this->sbUrl('reservations', ['customer_id' => 'eq.' . $id, 'select' => 'id', 'limit' => 1]));
    $hasReservations = $resRes->successful() && !empty($resRes->json());

    if ($hasReservations) {
        Session::flash('customer_action_error', 'This customer cannot be deleted because they have reservation records.');
        return redirect()->route('admin.index', ['section' => 'customers']);
    }

    $this->sb()->delete($this->sbUrl('customer_password_resets', ['customer_id' => 'eq.' . $id]));
    $this->sb()->delete($this->sbUrl('customers', ['id' => 'eq.' . $id]));

    Session::flash('customer_action_success', 'Customer profile deleted successfully.');
    return redirect()->route('admin.index', ['section' => 'customers']);
}

        return redirect()->route('admin.index');
    }

    public function index(Request $request)
    {
        $section    = $this->sanitizeSection($request->input('section'));
        $isLoggedIn = $this->isLoggedIn();

        if (!$isLoggedIn) {
            return view('admin.index', $this->emptyViewData($section));
        }

        // Redirect secretary away from restricted sections
        if ($this->isSecretary() && !in_array($section, $this->secretaryAllowedSections())) {
            $section = 'reservations';
        }

        // ===================== CUSTOMERS =====================
$customers = [];
$customerSearch = trim((string) $request->input('customer_search', ''));
$customerStatus = trim((string) $request->input('customer_status', 'all'));

if ($section === 'customers') {
try {
    $url = rtrim(config('services.supabase.url'), '/');
    $key = config('services.supabase.service_role_key');

    $query = [
        'select' => '*',
        'order' => 'created_at.desc',
    ];

    if ($customerStatus !== 'all') {
        $query['status'] = 'eq.' . $customerStatus;
    }

    if ($customerSearch !== '') {
        $query['or'] = '(name.ilike.*' . $customerSearch . '*,email.ilike.*' . $customerSearch . '*,phone.ilike.*' . $customerSearch . '*)';
    }

    $response = \Illuminate\Support\Facades\Http::timeout(8)
        ->withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
        ])
        ->get($url . '/rest/v1/customers', $query);

    $customers = $response->successful() ? $response->json() : [];

    $customers = array_map(function ($c) {
        return [
            'id' => $c['id'] ?? null,
            'auth_id' => $c['auth_id'] ?? null,
            'name' => $c['name'] ?? 'No name',
            'email' => $c['email'] ?? '',
            'phone' => $c['phone'] ?? '',
            'status' => $c['status'] ?? 'active',
            'disabled_reason' => $c['disabled_reason'] ?? null,
            'created_at' => $c['created_at'] ?? null,
        ];
    }, $customers);

} catch (\Throwable) {
    $customers = [];
}
}

        // ===================== RESERVATIONS =====================
        // ===================== RESERVATIONS =====================
try {
    $url = rtrim(config('services.supabase.url'), '/');
    $key = config('services.supabase.key');

    $response = \Illuminate\Support\Facades\Http::timeout(8)
        ->withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
        ])
        ->get($url . '/rest/v1/reservations', [
            'select' => '*,reservation_attachments(*)',
            'order' => 'created_at.desc',
        ]);

    $reservations = $response->successful() ? $response->json() : [];

    $reservations = array_map(function ($r) {
        $details = $r['details'] ?? [];

        if (is_string($details)) {
            $details = json_decode($details, true) ?? [];
        }

        $r['name'] = $r['name'] ?? ($details['name'] ?? null);
        $r['email'] = $r['email'] ?? ($details['email'] ?? null);
        $r['phone'] = $r['phone'] ?? ($details['phone'] ?? null);
        $r['notes'] = $r['notes'] ?? ($details['notes'] ?? null);
        $r['attachments'] = $r['reservation_attachments'] ?? [];

        return $r;
    }, $reservations);

} catch (\Throwable) {
    $reservations = [];
}

foreach ($customers as &$customer) {
    $customerReservations = array_values(array_filter($reservations, function ($r) use ($customer) {
        return (string)($r['customer_id'] ?? '') === (string)($customer['id'] ?? '');
    }));

    usort($customerReservations, function ($a, $b) {
        return strtotime($b['created_at'] ?? '1970-01-01') <=> strtotime($a['created_at'] ?? '1970-01-01');
    });

    $customer['total_reservations'] = count($customerReservations);
    $customer['latest_reservation'] = $customerReservations[0] ?? null;

    $customer['reservations'] = array_map(function ($r) {
        return [
            'id' => $r['id'] ?? null,
            'event_type' => $r['event_type'] ?? 'Unspecified',
            'status' => $r['status'] ?? 'pending',
            'reservation_date' => $r['reservation_date'] ?? null,
            'reservation_time' => $r['reservation_time'] ?? null,
            'created_at' => $r['created_at'] ?? null,
        ];
    }, $customerReservations);

    $eventTypeCounts = [];

    foreach ($customerReservations as $r) {
        $eventType = $r['event_type'] ?? 'Unspecified';
        $eventTypeCounts[$eventType] = ($eventTypeCounts[$eventType] ?? 0) + 1;
    }

    arsort($eventTypeCounts);

    $customer['favorite_event_type'] = !empty($eventTypeCounts)
        ? array_key_first($eventTypeCounts)
        : 'No reservations yet';

    $customer['approved_count'] = count(array_filter($customerReservations, fn ($r) => strtolower($r['status'] ?? '') === 'approved'));
    $customer['pending_count'] = count(array_filter($customerReservations, fn ($r) => strtolower($r['status'] ?? '') === 'pending'));
    $customer['declined_count'] = count(array_filter($customerReservations, fn ($r) => strtolower($r['status'] ?? '') === 'declined'));
}
unset($customer);

        $filterResult         = $this->applyReservationTimeFilter($reservations, $request->input('range'), $request->input('date'));
        $filteredReservations = $filterResult['reservations'];
        $reservationFilterRange = $filterResult['range'];
        $reservationFilterDate  = $filterResult['date'];
        $reservationFilterDesc  = $filterResult['description'];

        // Event type filter
        $reservationFilterType = strtolower(trim((string)$request->input('event_type', 'all')));
        if (!in_array($reservationFilterType, ['all','baptism','wedding','funeral','kumpil','first communion'])) $reservationFilterType = 'all';

        $reservationSort = $request->input('sort', 'latest');
        if (!in_array($reservationSort, ['latest', 'oldest'])) $reservationSort = 'latest';
        if ($reservationFilterType !== 'all') {
            $filteredReservations = array_values(array_filter($filteredReservations, fn($r) =>
                strtolower($r['event_type'] ?? '') === $reservationFilterType
            ));
        }

        // Cancellation request filter
        $cancelFilterActive  = $request->input('cancel_filter') === '1';
        $cancelRequestCount  = count(array_filter($reservations, fn($r) => !empty($r['cancellation_requested'])));
        if ($cancelFilterActive) {
            $filteredReservations = array_values(array_filter($filteredReservations, fn($r) =>
                !empty($r['cancellation_requested'])
            ));
        }

        $hasActiveFilter = $reservationFilterRange !== 'all' || $reservationFilterType !== 'all' || $cancelFilterActive || $reservationSort !== 'latest';

        $grouped         = $this->groupByStatus($reservations);
        $filteredGrouped = $this->groupByStatus($filteredReservations);

        $today = date('Y-m-d');

        // Sort all groups by submission date
        foreach (['pending', 'approved', 'declined'] as $sk) {
            usort($filteredGrouped[$sk], function ($a, $b) use ($reservationSort) {
                $da = $a['created_at'] ?? '';
                $db = $b['created_at'] ?? '';
                return $reservationSort === 'oldest' ? strcmp($da, $db) : strcmp($db, $da);
            });
        }

        $summaryTotals = [
            
            'total'    => count($reservations),
            'pending'  => count($grouped['pending']),
            'approved' => count($grouped['approved']),
            'declined' => count($grouped['declined']),
        ];
        $filteredTotals = [
            'total'    => count($filteredReservations),
            'pending'  => count($filteredGrouped['pending']),
            'approved' => count($filteredGrouped['approved']),
            'declined' => count($filteredGrouped['declined']),
        ];
        // NEW: Event type counts (ONLY approved)
$eventCounts = [
    'Wedding' => 0,
    'Baptism' => 0,
    'Funeral' => 0,
    'Kumpil' => 0,
    'First Communion' => 0,
];

foreach ($reservations as $r) {
    $type = strtolower(trim($r['event_type'] ?? ''));
    $status = strtolower(trim($r['status'] ?? ''));

    if ($status !== 'approved') continue;

    if ($type === 'wedding') $eventCounts['Wedding']++;
    if ($type === 'baptism') $eventCounts['Baptism']++;
    if ($type === 'funeral') $eventCounts['Funeral']++;
    if ($type === 'kumpil') $eventCounts['Kumpil']++;
    if ($type === 'first communion') $eventCounts['First Communion']++;
}

        $reservationCountSummary = sprintf('%d of %d reservations', $filteredTotals['total'], $summaryTotals['total']);
        $reservationHeaderTotals = $section === 'reservations' ? $filteredTotals : $summaryTotals;

        // ── Priests ────────────────────────────────────────────────────────
        $priests = [];
        if (in_array($section, ['reservations', 'schedule', 'reports'], true)) {
        try {
            $priestResp = $this->sb()->get($this->sbUrl('priests', ['select' => '*', 'order' => 'name.asc']));
            if ($priestResp->successful()) $priests = $priestResp->json() ?? [];
        } catch (\Exception $e) {}
        }

        // Schedule
        $today        = new DateTimeImmutable('today');
        $scheduleDate = $today;
        $sdInput      = $request->input('schedule_date');
        if ($sdInput) {
            $parsed = DateTimeImmutable::createFromFormat('Y-m-d', trim($sdInput));
            if ($parsed) $scheduleDate = $parsed;
        }

        $scheduleDateValue       = $scheduleDate->format('Y-m-d');
        $isTodaySchedule         = $scheduleDate->format('Y-m-d') === $today->format('Y-m-d');
        $scheduleDateDescription = $scheduleDate->format('l, F j, Y');
        $scheduleDateHeading     = $isTodaySchedule
            ? 'Today · ' . $scheduleDate->format('F j, Y')
            : $scheduleDate->format('l · F j, Y');

      $scheduleReservations = array_values(array_filter($reservations, function ($r) {
    $status = strtolower((string)($r['status'] ?? ''));

    return $status === 'approved';
}));

       $dailySchedule = $scheduleReservations;

        usort($dailySchedule, function ($a, $b) {
            $av = $this->timeSortValue($a['preferred_time'] ?? $a['reservation_time'] ?? null);
            $bv = $this->timeSortValue($b['preferred_time'] ?? $b['reservation_time'] ?? null);
            return $av !== $bv ? $av <=> $bv : strtolower((string)($a['name'] ?? '')) <=> strtolower((string)($b['name'] ?? ''));
        });

        $scheduleEventCounts = [];
        foreach ($dailySchedule as $r) {
            $et = trim((string)($r['event_type'] ?? '')) ?: 'Unspecified';
            $scheduleEventCounts[$et] = ($scheduleEventCounts[$et] ?? 0) + 1;
        }
        ksort($scheduleEventCounts, SORT_NATURAL | SORT_FLAG_CASE);
        $scheduleReservationCount = count($scheduleReservations);

        // Schedule stats bar
        $todayStr   = $today->format('Y-m-d');
        $weekStart  = $today->modify('monday this week')->format('Y-m-d');
        $weekEnd    = $today->modify('sunday this week')->format('Y-m-d');
        $monthStr   = $today->format('Y-m');

        $scheduleTodayCount = 0;
        $scheduleWeekCount  = 0;
        $scheduleMonthCount = 0;
        foreach ($scheduleReservations as $r) {
            $d = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
            if (!$d) continue;
            $ds = (new DateTimeImmutable((string)$d))->format('Y-m-d');
            if ($ds === $todayStr) $scheduleTodayCount++;
            if ($ds >= $weekStart && $ds <= $weekEnd) $scheduleWeekCount++;
            if (strpos($ds, $monthStr) === 0) $scheduleMonthCount++;
        }

        // Next 5 upcoming reservations
        $scheduleUpcoming = array_values(array_filter($scheduleReservations, function ($r) use ($todayStr) {
            $d = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
            if (!$d) return false;
            return (new DateTimeImmutable((string)$d))->format('Y-m-d') >= $todayStr;
        }));
        usort($scheduleUpcoming, function ($a, $b) {
            $da = (new DateTimeImmutable($a['preferred_date'] ?? $a['reservation_date'] ?? 'now'))->format('Y-m-d');
            $db = (new DateTimeImmutable($b['preferred_date'] ?? $b['reservation_date'] ?? 'now'))->format('Y-m-d');
            return $da <=> $db;
        });
        $scheduleUpcoming = array_slice($scheduleUpcoming, 0, 5);

        $scheduleCalendarJson = [];

        $scheduleCalendarReservations = array_values(array_filter($reservations, function ($r) {
            $status = strtolower((string)($r['status'] ?? ''));
            return $status === 'approved' || $status === 'pending';
        }));

foreach ($scheduleCalendarReservations as $r) {
    $date = $r['preferred_date'] ?? $r['reservation_date'] ?? null;

    if (!$date) {
        continue;
    }

    $dateKey = (new DateTimeImmutable((string) $date))->format('Y-m-d');
    $reservationDate = new DateTimeImmutable($dateKey);
    $isDone = $reservationDate < $today;

    $rStatus = strtolower((string)($r['status'] ?? 'pending'));
    $rDaysUntil = (int) ceil(($reservationDate->getTimestamp() - $today->getTimestamp()) / 86400);
    $rIsUrgent = $rStatus === 'pending' && $rDaysUntil >= 0 && $rDaysUntil <= 7;

    $rOfficiantId = (int)($r['officiant_id'] ?? 0);
    $rOfficiantName = '';
    foreach ($priests as $p) {
        if ((int)($p['id'] ?? 0) === $rOfficiantId) {
            $rOfficiantName = ($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? '');
            break;
        }
    }

    $rAttachments = collect($r['attachments'] ?? [])->map(function ($a) {
        $path = $a['file_url'] ?? '';
        $file = $path ? preg_replace('/^\d+_[a-zA-Z0-9]+_/', '', basename(parse_url($path, PHP_URL_PATH))) : '';
        return ['label' => $a['label'] ?? 'Attachment', 'file' => $file, 'path' => $path];
    })->values()->all();

    $rDetails = $r['details'] ?? [];
    if (is_string($rDetails)) $rDetails = json_decode($rDetails, true) ?? [];

    // Older bookings from the ParishSched mobile app saved these fields flat
    // (details.child_name, details.bride_name, ...) instead of nested like the
    // website does. Map them onto the nested shape the detail modal reads;
    // full names go in *_first since the modal joins first/middle/last.
    if (empty($rDetails['baptism']) && !empty($rDetails['child_name'])) {
        $rDetails['baptism'] = [
            'child_name'  => $rDetails['child_name'],
            'child_dob'   => $rDetails['child_dob'] ?? '',
            'father_name' => $rDetails['father_name'] ?? '',
            'mother_name' => $rDetails['mother_name'] ?? '',
        ];
    }
    if (empty($rDetails['wedding']) && (!empty($rDetails['groom_name']) || !empty($rDetails['bride_name']))) {
        $rDetails['wedding'] = [
            'groom_first'       => $rDetails['groom_name'] ?? '',
            'bride_first'       => $rDetails['bride_name'] ?? '',
            'seminar_date'      => $rDetails['seminar_date'] ?? '',
            'sacrament_details' => $rDetails['sacrament_details'] ?? '',
        ];
    }
    if (empty($rDetails['funeral']) && (!empty($rDetails['deceased_name']) || !empty($rDetails['marital_status']))) {
        $rDetails['funeral'] = [
            'deceased_first' => $rDetails['deceased_name'] ?? '',
            'marital_status' => $rDetails['marital_status'] ?? '',
        ];
    }

    try {
        $rDateFormatted = (new DateTimeImmutable((string) $date))->format('M j, Y');
    } catch (\Exception $e) {
        $rDateFormatted = (string) $date;
    }

    $rTimeRaw = trim((string)($r['preferred_time'] ?? $r['reservation_time'] ?? ''));
    $rTimeFormatted = '—';
    if ($rTimeRaw !== '') {
        $tParts = preg_split('/\s*-\s*/', $rTimeRaw);
        if (count($tParts) >= 2) {
            $fmtPart = function ($v) {
                $ts = strtotime(trim($v));
                return $ts !== false ? date('g:i A', $ts) : trim($v);
            };
            $rTimeFormatted = $fmtPart($tParts[0]) . ' – ' . $fmtPart($tParts[1]);
        } else {
            $ts = strtotime($rTimeRaw);
            $rTimeFormatted = $ts !== false ? date('g:i A', $ts) : $rTimeRaw;
        }
    }

    // Who receives the sacrament, shown under the customer's name in the
    // schedule time grid.
    $joinName = fn (array $parts) => trim(preg_replace('/\s+/', ' ', implode(' ', array_map('strval', $parts))));
    $rEvKey = strtolower((string) ($r['event_type'] ?? ''));
    $rSubject = match ($rEvKey) {
        'baptism' => ($rDetails['baptism']['child_name'] ?? '') ?: $joinName([
            $rDetails['baptism']['child_first'] ?? '', $rDetails['baptism']['child_middle'] ?? '',
            $rDetails['baptism']['child_last'] ?? '', $rDetails['baptism']['child_suffix'] ?? '',
        ]),
        'wedding' => implode(' & ', array_filter([
            $joinName([$rDetails['wedding']['groom_first'] ?? '', $rDetails['wedding']['groom_last'] ?? '']),
            $joinName([$rDetails['wedding']['bride_first'] ?? '', $rDetails['wedding']['bride_last'] ?? '']),
        ])),
        'funeral' => $joinName([
            $rDetails['funeral']['deceased_first'] ?? '', $rDetails['funeral']['deceased_middle'] ?? '',
            $rDetails['funeral']['deceased_last'] ?? '', $rDetails['funeral']['deceased_suffix'] ?? '',
        ]),
        'kumpil' => $joinName([
            $rDetails['kumpil']['confirmand_first'] ?? '', $rDetails['kumpil']['confirmand_middle'] ?? '',
            $rDetails['kumpil']['confirmand_last'] ?? '', $rDetails['kumpil']['confirmand_suffix'] ?? '',
        ]),
        'first communion' => !empty($rDetails['communion']['children']) ? $rDetails['communion']['children'] . ' children' : '',
        default => '',
    };

    $scheduleCalendarJson[$dateKey][] = [
        'id' => $r['id'] ?? null,
        'subject' => $rSubject,
        'name' => $r['name'] ?? $r['details']['name'] ?? 'No name provided',
        'email' => $r['email'] ?? $r['details']['email'] ?? 'Not provided',
        'phone' => $r['phone'] ?? $r['details']['phone'] ?? 'Not provided',
        'eventType' => $r['event_type'] ?? 'Unspecified',
        'time' => $r['preferred_time'] ?? $r['reservation_time'] ?? '—',
        'date' => $dateKey,
        'status' => $rStatus,
        'statusLabel' => $isDone ? 'Done' : 'Upcoming',
        'detail' => [
            'id'                => $r['id'] ?? null,
            'name'              => $r['name'] ?? $r['details']['name'] ?? 'No name provided',
            'email'             => $r['email'] ?? $r['details']['email'] ?? '',
            'phone'             => $r['phone'] ?? $r['details']['phone'] ?? '',
            'status'            => $rStatus,
            'event_type'        => $r['event_type'] ?? '',
            'date'              => $rDateFormatted,
            'date_day'          => $reservationDate->format('l'),
            'time'              => $rTimeFormatted,
            'officiant'         => $rOfficiantName,
            'notes'             => $r['notes'] ?? '',
            'admin_note'        => $r['admin_note'] ?? '',
            'is_urgent'         => $rIsUrgent,
            'is_one_day_before' => $reservationDate <= $today->modify('+1 day'),
            'created_at'        => $r['created_at'] ?? '',
            'attachments'       => $rAttachments,
            'requirements'      => \App\Support\ReservationRequirements::status($r['event_type'] ?? '', $r['attachments'] ?? [], $rDetails),
            'baptism'           => $rDetails['baptism'] ?? null,
            'wedding'           => $rDetails['wedding'] ?? null,
            'funeral'           => $rDetails['funeral'] ?? null,
            'kumpil'            => $rDetails['kumpil'] ?? null,
            'communion'         => $rDetails['communion'] ?? null,
            'status_label'      => $rStatus,
        ],
    ];
}

        // Announcements
        $announcements = [];
        if (in_array($section, ['overview', 'announcements'], true)) {
       try {
    $url = rtrim(config('services.supabase.url'), '/');
    $key = config('services.supabase.key');

    $response = \Illuminate\Support\Facades\Http::timeout(8)
        ->withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
        ])
        ->get($url . '/rest/v1/announcements', [
            'select' => '*',
            'order' => 'created_at.desc',
        ]);

    $announcements = $response->successful()
        ? json_decode(json_encode($response->json()))
        : [];
} catch (\Throwable) {
    $announcements = [];
}
        }
        $announcementCount        = count($announcements);
        $visibleAnnouncementCount = count(array_filter($announcements, fn($a) => (int)($a->show_on_home ?? 0) === 1));

        $recentReservations  = array_slice($reservations, 0, 5);
        $recentAnnouncements = array_slice($announcements, 0, 3);

        // ===================== REPORTS =====================
        $reportFromYear  = (int)($request->input('report_from_year',  now()->year));
        $reportFromMonth = (int)($request->input('report_from_month', 1));
        $reportToYear    = (int)($request->input('report_to_year',    now()->year));
        $reportToMonth   = (int)($request->input('report_to_month',   now()->month));
        $reportFromMonth = max(1, min(12, $reportFromMonth));
        $reportToMonth   = max(1, min(12, $reportToMonth));
        if ($reportFromYear * 100 + $reportFromMonth > $reportToYear * 100 + $reportToMonth) {
            [$reportFromYear, $reportToYear]     = [$reportToYear,    $reportFromYear];
            [$reportFromMonth, $reportToMonth]   = [$reportToMonth,   $reportFromMonth];
        }
        $reportYear  = $reportFromYear;
        $reportMonth = 0;
        $reportType  = strtolower(trim((string)$request->input('report_type', 'all')));
        if (!in_array($reportType, ['all','baptism','wedding','funeral','kumpil','communion'])) $reportType = 'all';

        $rptMonths     = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $rptMonthsFull = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        // Build ordered list of months in range
        $rangeMonths = [];
        $curY = $reportFromYear; $curM = $reportFromMonth;
        while ($curY * 100 + $curM <= $reportToYear * 100 + $reportToMonth) {
            $rangeMonths[] = [
                'year'  => $curY,
                'month' => $curM,
                'label' => $rptMonths[$curM - 1] . ' ' . $curY,
                'pad'   => str_pad((string)$curM, 2, '0', STR_PAD_LEFT),
            ];
            if (++$curM > 12) { $curM = 1; $curY++; }
        }

        $reportRangeLabel = $rptMonthsFull[$reportFromMonth - 1] . ' ' . $reportFromYear;
        if ($reportFromYear !== $reportToYear || $reportFromMonth !== $reportToMonth) {
            $reportRangeLabel .= ' – ' . $rptMonthsFull[$reportToMonth - 1] . ' ' . $reportToYear;
        }

        $yearReservations = array_values(array_filter($reservations, function ($r) use ($reportFromYear, $reportFromMonth, $reportToYear, $reportToMonth, $reportType) {
            $date = (string)($r['reservation_date'] ?? '');
            if (strlen($date) < 7) return false;
            $ym = (int) substr($date, 0, 4) * 100 + (int) substr($date, 5, 2);
            if ($ym < $reportFromYear * 100 + $reportFromMonth || $ym > $reportToYear * 100 + $reportToMonth) return false;
            if ($reportType !== 'all' && self::typeKey($r['event_type'] ?? '') !== $reportType) return false;
            return true;
        }));

        $reportMonthlyData = [];
        foreach ($rangeMonths as $rm) {
            $monthRes = array_filter($yearReservations, fn($r) =>
                substr((string)($r['reservation_date'] ?? ''), 0, 7) === "{$rm['year']}-{$rm['pad']}"
            );
            $reportMonthlyData[$rm['label']] = [
                'baptism'  => count(array_filter($monthRes, fn($r) => strtolower($r['event_type'] ?? '') === 'baptism')),
                'wedding'  => count(array_filter($monthRes, fn($r) => strtolower($r['event_type'] ?? '') === 'wedding')),
                'funeral'  => count(array_filter($monthRes, fn($r) => strtolower($r['event_type'] ?? '') === 'funeral')),
                'kumpil'   => count(array_filter($monthRes, fn($r) => self::typeKey($r['event_type'] ?? '') === 'kumpil')),
                'communion'=> count(array_filter($monthRes, fn($r) => self::typeKey($r['event_type'] ?? '') === 'communion')),
                'approved' => count(array_filter($monthRes, fn($r) => strtolower($r['status'] ?? '') === 'approved')),
                'pending'  => count(array_filter($monthRes, fn($r) => strtolower($r['status'] ?? '') === 'pending')),
                'declined' => count(array_filter($monthRes, fn($r) => strtolower($r['status'] ?? '') === 'declined')),
            ];
        }

        $reportTotals = [
            'baptism'  => array_sum(array_column($reportMonthlyData, 'baptism')),
            'wedding'  => array_sum(array_column($reportMonthlyData, 'wedding')),
            'funeral'  => array_sum(array_column($reportMonthlyData, 'funeral')),
            'kumpil'   => array_sum(array_column($reportMonthlyData, 'kumpil')),
            'communion'=> array_sum(array_column($reportMonthlyData, 'communion')),
            'approved' => array_sum(array_column($reportMonthlyData, 'approved')),
            'pending'  => array_sum(array_column($reportMonthlyData, 'pending')),
            'declined' => array_sum(array_column($reportMonthlyData, 'declined')),
        ];
        $reportTotals['total'] = $reportTotals['baptism'] + $reportTotals['wedding'] + $reportTotals['funeral']
            + $reportTotals['kumpil'] + $reportTotals['communion'];

        $rptDayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $reportDayData = [];
        foreach ($rptDayNames as $d) {
            $reportDayData[$d] = ['baptism'=>0,'wedding'=>0,'funeral'=>0,'kumpil'=>0,'communion'=>0,'total'=>0];
        }
        foreach ($yearReservations as $r) {
            $rd = $r['reservation_date'] ?? '';
            if (!$rd) continue;
            try {
                $dow  = (int)(new \DateTimeImmutable((string)$rd))->format('w');
                $day  = $rptDayNames[$dow];
                $type = self::typeKey($r['event_type'] ?? '') ?? '';
                if ($type !== '' && array_key_exists($type, $reportDayData[$day])) {
                    $reportDayData[$day][$type]++;
                }
                $reportDayData[$day]['total']++;
            } catch (\Throwable) {}
        }

        // AI SUMMARY — powered by Google Gemini
        $rptTotalForAi  = $reportTotals['total'];
        $rptPeakForAi   = '—'; $rptPeakValAi = 0;
        foreach ($reportMonthlyData as $mn => $md) {
            $mt = $md['baptism'] + $md['wedding'] + $md['funeral'] + $md['kumpil'] + $md['communion'];
            if ($mt > $rptPeakValAi) { $rptPeakValAi = $mt; $rptPeakForAi = $mn; }
        }
        $rptBusiestForAi = '—'; $rptBusiestMaxAi = 0;
        foreach ($reportDayData as $d => $dd) {
            if ($dd['total'] > $rptBusiestMaxAi) { $rptBusiestMaxAi = $dd['total']; $rptBusiestForAi = $d; }
        }

        $reportSummary = null;
        $geminiKey = config('services.gemini.key');

        // Only the Reports page shows the AI summary. A failed or rate-limited
        // answer is remembered for 10 minutes (as ''), so a slow AI service
        // never holds up every admin page load.
        if ($section === 'reports' && $rptTotalForAi > 0 && $geminiKey && \Illuminate\Support\Facades\Cache::get($cacheKey = "report_ai_summary_{$reportFromYear}_{$reportFromMonth}_{$reportToYear}_{$reportToMonth}_{$reportType}") !== '') {
            $cacheKey = "report_ai_summary_{$reportFromYear}_{$reportFromMonth}_{$reportToYear}_{$reportToMonth}_{$reportType}";

            $reportSummary = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use (
                $reportRangeLabel, $reportType,
                $reportTotals, $rptTotalForAi,
                $rptPeakForAi, $rptPeakValAi,
                $rptBusiestForAi, $rptBusiestMaxAi,
                $reportMonthlyData, $geminiKey
            ) {
                $monthlyBreakdown = collect($reportMonthlyData)
                    ->map(fn($d, $m) => "{$m}: {$d['baptism']} baptism, {$d['wedding']} wedding, {$d['funeral']} funeral, {$d['kumpil']} kumpil, {$d['communion']} first communion")
                    ->implode(' | ');

                $filterNote = " Date range: {$reportRangeLabel}.";
                if ($reportType !== 'all') $filterNote .= " Filtered to {$reportType} only.";

                $prompt = "You are a concise parish records analyst. Write exactly 2-3 sentences summarizing this sacrament reservation report. Be specific — mention actual numbers and trends. No bullet points, no markdown, plain text only.{$filterNote}

Period: {$reportRangeLabel}
Total sacraments: {$rptTotalForAi}
Baptisms: {$reportTotals['baptism']} | Weddings: {$reportTotals['wedding']} | Funerals: {$reportTotals['funeral']} | Kumpil: {$reportTotals['kumpil']} | First Communion: {$reportTotals['communion']}
Approved: {$reportTotals['approved']} | Pending: {$reportTotals['pending']} | Declined: {$reportTotals['declined']}
Peak month: {$rptPeakForAi} ({$rptPeakValAi} total)
Busiest day: {$rptBusiestForAi} ({$rptBusiestMaxAi} sacraments)
Monthly breakdown: {$monthlyBreakdown}";

                try {
                    $response = \Illuminate\Support\Facades\Http::connectTimeout(3)->timeout(6)
                        ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$geminiKey}", [
                            'contents' => [
                                ['parts' => [['text' => $prompt]]]
                            ],
                        ]);

                    if ($response->successful()) {
                        return $response->json('candidates.0.content.parts.0.text');
                    }
                } catch (\Throwable) {}

                return null;
            });
            if ($reportSummary === null) {
                \Illuminate\Support\Facades\Cache::put($cacheKey, '', 600);
            }
        }

        // ── Donations (all, for the donations section) ─────────────────────
        $allDonationsRaw = [];
        if (in_array($section, ['donations', 'reports'], true)) {
        try {
            $donResp = $this->sb()->get(config('services.supabase.url') . '/rest/v1/donations', [
                'select' => '*',
                'order'  => 'donation_date.desc,created_at.desc',
            ]);
            if ($donResp->successful()) {
                $allDonationsRaw = $donResp->json() ?? [];
            }
        } catch (\Throwable) {}
        }

        // Donations section filters
        $donationSearch  = trim((string)$request->input('donation_search', ''));
        $donationPurpose = $request->input('donation_purpose', 'all');
        $donationFrom    = $request->input('donation_from', '');
        $donationTo      = $request->input('donation_to', '');

        $donationsList = array_values(array_filter($allDonationsRaw, function ($d) use ($donationSearch, $donationPurpose, $donationFrom, $donationTo) {
            if ($donationSearch && stripos((string)($d['donor_name'] ?? ''), $donationSearch) === false) return false;
            if ($donationPurpose !== 'all' && ($d['purpose'] ?? '') !== $donationPurpose) return false;
            if ($donationFrom && ($d['donation_date'] ?? '') < $donationFrom) return false;
            if ($donationTo   && ($d['donation_date'] ?? '') > $donationTo)   return false;
            return true;
        }));

        // Totals count only money that reached the parish (not pending GCash,
        // uncleared cheques or rejected entries).
        $countedDonationsList = array_filter($donationsList, [DonationService::class, 'isCounted']);
        $donationSectionTotal = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), $countedDonationsList));
        $donationsByPurpose   = [];
        foreach (['building_fund','mass_intention','tithes','other'] as $p) {
            $donationsByPurpose[$p] = array_sum(array_map(
                fn($d) => (float)($d['amount'] ?? 0),
                array_filter($countedDonationsList, fn($d) => ($d['purpose'] ?? 'other') === $p)
            ));
        }

        // Donations for reports chart (filtered by range, grouped by purpose)
        $donationsForYear = array_filter(array_filter($allDonationsRaw, [DonationService::class, 'isCounted']), function ($d) use ($reportFromYear, $reportFromMonth, $reportToYear, $reportToMonth) {
            $dd = (string)($d['donation_date'] ?? '');
            if (strlen($dd) < 7) return false;
            $ym = (int) substr($dd, 0, 4) * 100 + (int) substr($dd, 5, 2);
            return $ym >= $reportFromYear * 100 + $reportFromMonth && $ym <= $reportToYear * 100 + $reportToMonth;
        });
        $donationMonthly = [];
        foreach ($rangeMonths as $rm) {
            $md = array_filter($donationsForYear, fn($d) => substr((string)($d['donation_date'] ?? ''), 0, 7) === "{$rm['year']}-{$rm['pad']}");
            $bf = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), array_filter($md, fn($d) => ($d['purpose'] ?? '') === 'building_fund')));
            $mi = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), array_filter($md, fn($d) => ($d['purpose'] ?? '') === 'mass_intention')));
            $ti = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), array_filter($md, fn($d) => ($d['purpose'] ?? '') === 'tithes')));
            $ot = array_sum(array_map(fn($d) => (float)($d['amount'] ?? 0), array_filter($md, fn($d) => ($d['purpose'] ?? 'other') === 'other')));
            $donationMonthly[$rm['label']] = [
                'building_fund'  => $bf,
                'mass_intention' => $mi,
                'tithes'         => $ti,
                'other'          => $ot,
                'total'          => $bf + $mi + $ti + $ot,
            ];
        }
        $donationTotal   = array_sum(array_column($donationMonthly, 'total'));
        $recentDonations = array_values(array_slice($donationsList, 0, 8));

        // ── Schedule Utilization ────────────────────────────────────────────
        // Capacity: baptism=5 slots/day + wedding=1/day + funeral=1/day = 7/day
        $allYearRes = $yearReservations;
        $utilizationMonthly = [];
        foreach ($rangeMonths as $rm) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $rm['month'], $rm['year']);
            $capacity    = $daysInMonth * 7;
            $monthRes    = array_filter($allYearRes, fn($r) => substr((string)($r['reservation_date'] ?? ''), 0, 7) === "{$rm['year']}-{$rm['pad']}");
            $approved    = count(array_filter($monthRes, fn($r) => strtolower($r['status'] ?? '') === 'approved'));
            $utilizationMonthly[$rm['label']] = [
                'capacity' => $capacity,
                'approved' => $approved,
                'rate'     => $capacity > 0 ? round(($approved / $capacity) * 100, 1) : 0,
            ];
        }

        // Officiant workload: count approved reservations per priest
        $officiantWorkload = [];
        foreach ($priests as $p) {
            $pid      = (int)($p['id'] ?? 0);
            $assigned = array_filter($scheduleReservations, fn($r) => (int)($r['officiant_id'] ?? 0) === $pid);
            $baptisms = count(array_filter($assigned, fn($r) => strtolower($r['event_type'] ?? '') === 'baptism'));
            $weddings = count(array_filter($assigned, fn($r) => strtolower($r['event_type'] ?? '') === 'wedding'));
            $funerals = count(array_filter($assigned, fn($r) => strtolower($r['event_type'] ?? '') === 'funeral'));
            $kumpils  = count(array_filter($assigned, fn($r) => self::typeKey($r['event_type'] ?? '') === 'kumpil'));
            $communions = count(array_filter($assigned, fn($r) => self::typeKey($r['event_type'] ?? '') === 'communion'));
            $officiantWorkload[] = [
                'id'       => $pid,
                'name'     => ($p['title'] ?? 'Fr.') . ' ' . ($p['name'] ?? ''),
                'count'    => $baptisms + $weddings + $funerals + $kumpils + $communions,
                'baptisms' => $baptisms,
                'weddings' => $weddings,
                'funerals' => $funerals,
                'kumpils'  => $kumpils,
                'communions' => $communions,
                'active'   => $p['active'] ?? true,
            ];
        }
        usort($officiantWorkload, fn($a, $b) => $b['count'] <=> $a['count']);

        // ── Event Attendance ───────────────────────────────────────────────
        $attendanceRaw = [];
        if ($section === 'reports') {
        try {
            $attResp = $this->sb()->get($this->sbUrl('event_attendance', ['select' => '*', 'order' => 'recorded_at.desc']));
            if ($attResp->successful()) $attendanceRaw = $attResp->json() ?? [];
        } catch (\Exception $e) {}
        }

        // Lookup: reservation_id => attendance row
        $attendanceLookup = [];
        foreach ($attendanceRaw as $a) {
            $attendanceLookup[(int)($a['reservation_id'] ?? 0)] = $a;
        }

        // Past approved reservations for the log table
        $pastApproved = array_values(array_filter($scheduleReservations, function ($r) use ($todayStr) {
            $d = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
            if (!$d) return false;
            return (new DateTimeImmutable((string)$d))->format('Y-m-d') < $todayStr;
        }));
        usort($pastApproved, function ($a, $b) {
            $da = (new DateTimeImmutable($a['preferred_date'] ?? $a['reservation_date'] ?? 'now'))->format('Y-m-d');
            $db = (new DateTimeImmutable($b['preferred_date'] ?? $b['reservation_date'] ?? 'now'))->format('Y-m-d');
            return $db <=> $da; // newest first
        });

        // Avg attendance by event type for chart
        $attendanceByType = ['Wedding' => [], 'Baptism' => [], 'Funeral' => [], 'Kumpil' => [], 'First Communion' => []];
        foreach ($attendanceLookup as $a) {
            $et = ucwords(strtolower($a['event_type'] ?? ''));
            if (isset($attendanceByType[$et])) {
                $attendanceByType[$et][] = (int)($a['attended_count'] ?? 0);
            }
        }
        $attendanceAvg = [];
        foreach ($attendanceByType as $et => $counts) {
            $attendanceAvg[$et] = count($counts) > 0 ? round(array_sum($counts) / count($counts)) : 0;
        }

        $adminRole = Session::get('admin_role', 'admin');

        // Admin → Schedule → Edit on a First Communion fills the add form.
        $editCommunion = null;
        if ($section === 'schedule' && $request->filled('edit_communion')) {
            foreach ($reservations as $r) {
                if ((string) ($r['id'] ?? '') === (string) $request->input('edit_communion')
                    && strcasecmp((string) ($r['event_type'] ?? ''), 'First Communion') === 0) {
                    $editCommunion = $r;
                    break;
                }
            }
        }

        $reportRecommendations = $section === 'reports'
            ? \App\Support\ReportRecommendations::build(
                $reportMonthlyData, $reportDayData, $reportTotals, $yearReservations, $reservations, $attendanceAvg, $officiantWorkload
            )
            : [];

        return view('admin.index', compact(
            'section', 'isLoggedIn', 'adminRole',
            'reservations', 'filteredReservations',
            'grouped', 'filteredGrouped',
            'summaryTotals', 'filteredTotals',
            'reservationCountSummary', 'reservationHeaderTotals',
            'reservationFilterRange', 'reservationFilterDate', 'reservationFilterType',
            'reservationFilterDesc', 'hasActiveFilter', 'reservationSort',
            'cancelFilterActive', 'cancelRequestCount',
            'announcements', 'announcementCount', 'visibleAnnouncementCount',
            'recentReservations', 'recentAnnouncements',
            'scheduleDateValue', 'scheduleDateHeading', 'scheduleDateDescription',
            'dailySchedule', 'scheduleEventCounts', 'scheduleReservationCount',
            'scheduleCalendarJson',
            'scheduleTodayCount', 'scheduleWeekCount', 'scheduleMonthCount', 'scheduleUpcoming',
            'eventCounts',
            'customers',
            'customerSearch',
            'customerStatus',
            'reportYear', 'reportMonth', 'reportType', 'reportMonthlyData', 'reportTotals', 'reportDayData', 'reportSummary',
            'reportFromYear', 'reportFromMonth', 'reportToYear', 'reportToMonth', 'reportRangeLabel',
            'donationMonthly', 'donationTotal', 'recentDonations',
            'utilizationMonthly',
            'pastApproved', 'attendanceLookup', 'attendanceAvg',
            'donationsList', 'donationSearch', 'donationPurpose', 'donationFrom', 'donationTo',
            'donationSectionTotal', 'donationsByPurpose',
            'priests', 'officiantWorkload', 'reportRecommendations', 'editCommunion'
        ));
    }

    // ── Reports export ───────────────────────────────────────────────

    public function exportReportPdf(Request $request)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->route('admin.index');
        }

        $reportFromYear  = (int)($request->input('report_from_year',  now()->year));
        $reportFromMonth = (int)($request->input('report_from_month', 1));
        $reportToYear    = (int)($request->input('report_to_year',    now()->year));
        $reportToMonth   = (int)($request->input('report_to_month',   now()->month));
        $reportFromMonth = max(1, min(12, $reportFromMonth));
        $reportToMonth   = max(1, min(12, $reportToMonth));
        if ($reportFromYear * 100 + $reportFromMonth > $reportToYear * 100 + $reportToMonth) {
            [$reportFromYear, $reportToYear]   = [$reportToYear,    $reportFromYear];
            [$reportFromMonth, $reportToMonth] = [$reportToMonth,   $reportFromMonth];
        }
        $reportType = strtolower(trim((string)$request->input('report_type', 'all')));
        if (!in_array($reportType, ['all', 'baptism', 'wedding', 'funeral', 'kumpil', 'communion'])) $reportType = 'all';

        try {
            $response = $this->sb()->timeout(8)->get($this->sbUrl('reservations', [
                'select' => '*',
                'order'  => 'reservation_date.asc',
            ]));
            $reservations = $response->successful() ? $response->json() : [];

            $reservations = array_map(function ($r) {
                $details = $r['details'] ?? [];
                if (is_string($details)) {
                    $details = json_decode($details, true) ?? [];
                }
                $r['name']  = $r['name']  ?? ($details['name']  ?? null);
                $r['email'] = $r['email'] ?? ($details['email'] ?? null);
                $r['phone'] = $r['phone'] ?? ($details['phone'] ?? null);
                return $r;
            }, $reservations);
        } catch (\Throwable) {
            $reservations = [];
        }

        $filtered = array_values(array_filter($reservations, function ($r) use ($reportFromYear, $reportFromMonth, $reportToYear, $reportToMonth, $reportType) {
            $date = (string)($r['reservation_date'] ?? '');
            if (strlen($date) < 7) return false;
            $ym = (int) substr($date, 0, 4) * 100 + (int) substr($date, 5, 2);
            if ($ym < $reportFromYear * 100 + $reportFromMonth || $ym > $reportToYear * 100 + $reportToMonth) return false;
            if ($reportType !== 'all' && self::typeKey($r['event_type'] ?? '') !== $reportType) return false;
            return true;
        }));

        $rptMonthsFull = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $reportRangeLabel = $rptMonthsFull[$reportFromMonth - 1] . ' ' . $reportFromYear;
        if ($reportFromYear !== $reportToYear || $reportFromMonth !== $reportToMonth) {
            $reportRangeLabel .= ' – ' . $rptMonthsFull[$reportToMonth - 1] . ' ' . $reportToYear;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports-pdf', [
            'reservations'     => $filtered,
            'reportRangeLabel' => $reportRangeLabel,
            'reportType'       => $reportType,
            'generatedAt'      => now()->format('F j, Y g:i A'),
            'logoPath'         => public_path('img/about/about_1.jpg'),
        ])->setPaper('a4', 'landscape')->setOption(['isPhpEnabled' => true]);

        return $pdf->download('parish-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // ── Donations ─────────────────────────────────────────────────────

    public function storeDonation(Request $request)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->route('admin.index');
        }

        $validated = $request->validate([
            'donor_name'     => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0.01',
            'purpose'        => 'required|in:building_fund,mass_intention,tithes,other',
            'custom_purpose' => 'nullable|string|max:255',
            'donation_date'  => 'required|date',
            'notes'          => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,gcash,cheque',
            'gcash_reference'=> 'required_if:payment_method,gcash|nullable|string|max:40',
            'cheque_number'  => 'required_if:payment_method,cheque|nullable|string|max:40',
            'cheque_bank'    => 'required_if:payment_method,cheque|nullable|string|max:120',
            'cheque_date'    => 'nullable|date',
        ], [
            'gcash_reference.required_if' => 'Enter the GCash reference number.',
            'cheque_number.required_if'   => 'Enter the cheque number.',
            'cheque_bank.required_if'     => 'Enter the bank of the cheque.',
        ]);

        // Payment method: staff recording a GCash payment have seen it in the
        // parish account, so it is verified; a cheque starts uncleared.
        $method = $validated['payment_method'];
        $validated['payment_status'] = ['cash' => 'received', 'gcash' => 'verified', 'cheque' => 'uncleared'][$method];
        $validated['source'] = 'admin';
        if ($method !== 'gcash') {
            $validated['gcash_reference'] = null;
        } else {
            $validated['gcash_reference'] = preg_replace('/\s+/', '', $validated['gcash_reference']);
            if (app(DonationService::class)->gcashReferenceUsed($validated['gcash_reference'])) {
                Session::flash('donation_action_error', 'This GCash reference number was already recorded.');
                return redirect()->route('admin.index', ['section' => 'donations']);
            }
        }
        if ($method !== 'cheque') {
            $validated['cheque_number'] = $validated['cheque_bank'] = $validated['cheque_date'] = null;
        }

        // DUPLICATE ENTRY CHECK: same donor + same amount + same date
        $dupCheck = $this->sb()->get(config('services.supabase.url') . '/rest/v1/donations', [
            'select'        => 'id,receipt_number',
            'donor_name'    => 'eq.' . $validated['donor_name'],
            'amount'        => 'eq.' . $validated['amount'],
            'donation_date' => 'eq.' . $validated['donation_date'],
            'limit'         => 1,
        ]);

        if ($dupCheck->successful() && !empty($dupCheck->json())) {
            $existing = $dupCheck->json()[0];
            $receipt  = $existing['receipt_number'] ?? 'unknown';
            Session::flash('donation_action_error',
                "A donation from \"{$validated['donor_name']}\" for the same amount on this date already exists (Receipt #{$receipt}). Please verify before saving again.");
            return redirect()->route('admin.index', ['section' => 'donations']);
        }

        // Auto-generate receipt number: RCP-{YEAR}-{sequential}
        $validated['receipt_number'] = app(DonationService::class)
            ->nextReceiptNumber((int) substr($validated['donation_date'], 0, 4));

        $response = app(DonationService::class)->insert($validated);

        // Until supabase/donation_payments.sql is run, cash donations still
        // save with the original columns.
        if ($response->failed() && $method === 'cash' && str_contains((string) $response->json('message'), 'column')) {
            $response = app(DonationService::class)->insert(array_intersect_key($validated, array_flip([
                'donor_name', 'amount', 'purpose', 'custom_purpose', 'donation_date', 'notes', 'receipt_number',
            ])));
        }

        if ($response->failed()) {
            Session::flash('donation_action_error', DonationService::insertError($response));
            return redirect()->route('admin.index', ['section' => 'donations']);
        }

        return redirect()->route('admin.index', ['section' => 'donations'])
            ->with('donation_action_success', 'Donation recorded. Receipt #' . $validated['receipt_number']);
    }

    /**
     * Verify or reject a GCash donation, or mark a cheque cleared/rejected.
     */
    public function updateDonationStatus(Request $request, string $id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->route('admin.index');
        }

        $validated = $request->validate(['payment_status' => 'required|in:verified,cleared,rejected']);

        $response = $this->sb()->patch(config('services.supabase.url') . '/rest/v1/donations?id=eq.' . (int) $id, [
            'payment_status' => $validated['payment_status'],
        ]);

        if ($response->failed()) {
            Session::flash('donation_action_error', 'Could not update the donation: ' . ($response->json('message') ?? $response->body()));
            return redirect()->route('admin.index', ['section' => 'donations']);
        }

        $label = DonationService::STATUSES[$validated['payment_status']];

        return redirect()->route('admin.index', ['section' => 'donations'])
            ->with('donation_action_success', "Donation marked as {$label}.");
    }

    public function deleteDonation(string $id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->route('admin.index');
        }

        $this->sb()->delete(config('services.supabase.url') . '/rest/v1/donations?id=eq.' . $id);

        return redirect()->route('admin.index', ['section' => 'donations'])
            ->with('donation_action_success', 'Donation record deleted.');
    }

    // ── Private helpers ───────────────────────────────────────────────

    public function storeAttendance(Request $request)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->route('admin.index');
        }

        $validated = $request->validate([
            'reservation_id' => 'required|integer',
            'event_type'     => 'required|string',
            'attended_count' => 'required|integer|min:0',
            'notes'          => 'nullable|string|max:300',
        ]);

        // Upsert — update if exists, insert if not
        $existing = $this->sb()->get($this->sbUrl('event_attendance', [
            'reservation_id' => 'eq.' . $validated['reservation_id'],
            'select'         => 'id',
        ]));

        $payload = [
            'reservation_id' => (int)$validated['reservation_id'],
            'event_type'     => $validated['event_type'],
            'attended_count' => (int)$validated['attended_count'],
            'notes'          => $validated['notes'] ?? null,
            'recorded_at'    => now()->toIso8601String(),
        ];

        if ($existing->successful() && count($existing->json() ?? []) > 0) {
            $this->sb()->withHeaders(['Prefer' => 'return=minimal'])
                ->patch($this->sbUrl('event_attendance', ['reservation_id' => 'eq.' . $validated['reservation_id']]), $payload);
        } else {
            $this->sb()->withHeaders(['Content-Type' => 'application/json', 'Prefer' => 'return=minimal'])
                ->post($this->sbUrl('event_attendance'), $payload);
        }

        return redirect()->route('admin.index', ['section' => 'reports'])->with('attendance_action_success', 'Attendance logged successfully.');
    }

    public function storePriest(Request $request)
    {
        if (!$this->isLoggedIn() || !$this->isAdmin()) {
            return redirect()->route('admin.index')->with('flash_error', 'Unauthorized.');
        }
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'title' => 'nullable|string|max:50',
        ]);
        $resp = $this->sb()->withHeaders(['Prefer' => 'return=minimal'])
            ->post($this->sbUrl('priests'), [
                'name'       => trim($validated['name']),
                'title'      => trim($validated['title'] ?? 'Fr.'),
                'active'     => true,
                'created_at' => now()->toISOString(),
            ]);
        Session::flash($resp->successful() ? 'priest_action_success' : 'priest_action_error',
            $resp->successful() ? 'Priest added to the roster.' : 'Failed to add priest: ' . $resp->body());
        return redirect()->route('admin.index', ['section' => 'reports', 'show_priest_manager' => '1']);
    }

    public function deletePriest(string $id)
    {
        if (!$this->isLoggedIn() || !$this->isAdmin()) {
            return redirect()->route('admin.index')->with('flash_error', 'Unauthorized.');
        }
        $resp = $this->sb()->delete($this->sbUrl('priests', ['id' => 'eq.' . (int)$id]));
        Session::flash($resp->successful() ? 'priest_action_success' : 'priest_action_error',
            $resp->successful() ? 'Priest removed from the roster.' : 'Failed to remove priest.');
        return redirect()->route('admin.index', ['section' => 'reports', 'show_priest_manager' => '1']);
    }

    public function assignOfficiant(Request $request, string $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->route('admin.index')->with('flash_error', 'Unauthorized.');
        }

        $officiantId = $request->input('officiant_id');

        // Priest double-booking check — only when actually assigning (not removing)
        if ($officiantId) {
            // Fetch the target reservation's date and time
            $targetRes = $this->sb()->get($this->sbUrl('reservations', [
                'id'     => 'eq.' . (int)$id,
                'select' => 'reservation_date,reservation_time',
                'limit'  => 1,
            ]));

            if ($targetRes->successful() && !empty($targetRes->json()[0])) {
                $target = $targetRes->json()[0];
                $date   = $target['reservation_date'] ?? null;
                $time   = $target['reservation_time'] ?? null;

                if ($date && $time) {
                    // Fetch all reservations already assigned to this priest on the same date
                    $conflictRes = $this->sb()->get($this->sbUrl('reservations', [
                        'select'           => 'id,event_type,reservation_time',
                        'officiant_id'     => 'eq.' . (int)$officiantId,
                        'reservation_date' => 'eq.' . $date,
                        'status'           => 'in.(approved,pending)',
                    ]));

                    $selectedTime = strtolower(trim(str_replace('–', '-', $time)));

                    $conflicts = collect($conflictRes->json() ?? [])->filter(function ($r) use ($selectedTime, $id) {
                        // Skip the reservation being edited itself
                        if ((int)($r['id'] ?? 0) === (int)$id) return false;
                        $existing = strtolower(trim(str_replace('–', '-', $r['reservation_time'] ?? '')));
                        return $existing === $selectedTime;
                    });

                    if ($conflicts->isNotEmpty()) {
                        $conflict = $conflicts->first();
                        Session::flash('flash_error',
                            'This priest is already assigned to a ' . ucfirst($conflict['event_type'] ?? 'event') .
                            ' on the same date and time. Please choose a different priest.');
                        return redirect()->route('admin.index', ['section' => 'reservations']);
                    }
                }
            }
        }

        $resp = $this->sb()->withHeaders(['Prefer' => 'return=minimal'])
            ->patch($this->sbUrl('reservations', ['id' => 'eq.' . (int)$id]), [
                'officiant_id' => $officiantId ? (int)$officiantId : null,
                'updated_at'   => now()->toISOString(),
            ]);
        if ($resp->successful()) {
            Session::flash($officiantId ? 'offi_assigned' : 'offi_removed', true);
        } else {
            Session::flash('flash_error', 'Failed to assign officiant: ' . $resp->body());
        }
        return redirect()->route('admin.index', ['section' => 'reservations']);
    }

    /**
     * Admin → Schedule: the parish office adds a First Communion for a school
     * batch. Families never book it themselves. It is saved as approved, and
     * it is blocked when another reservation overlaps that date and time.
     */
    public function addCommunion(Request $request)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->route('admin.index')->with('flash_error', 'Unauthorized.');
        }
        // Results show in the parish status pop-up (same as Approve / Decline).
        $back = fn (string $msg) => redirect()->route('admin.index', array_filter(['section' => 'schedule', 'edit_communion' => $request->input('id')]))
            ->withInput()->with(['status_update_error' => $msg, 'status_update_title' => $request->filled('id') ? 'Could Not Save Changes' : 'Could Not Add First Communion']);

        $check = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'id'        => 'nullable|integer',
            'school'    => 'required|string|max:200',
            'catechist' => 'required|string|max:200',
            'children'  => 'required|integer|min:1|max:500',
            'date'      => 'required|date|after_or_equal:today',
            'start'     => 'required|string',
            'end'       => 'required|string',
            'phone'     => 'nullable|string|max:50',
            'notes'     => 'nullable|string|max:500',
        ], [
            'school.required'       => 'Please enter the school.',
            'catechist.required'    => "Please enter the catechist's name.",
            'children.required'     => 'Please enter the number of children.',
            'date.required'         => 'Please choose the date.',
            'date.after_or_equal'   => 'The date cannot be in the past.',
        ]);
        if ($check->fails()) {
            return $back($check->errors()->first());
        }
        $data = $check->validated();
        $editId = !empty($data['id']) ? (int) $data['id'] : null;
        if ($editId) {
            $existing = $this->sb()->get($this->sbUrl('reservations', ['id' => 'eq.' . $editId, 'select' => 'id,event_type', 'limit' => 1]));
            if (strcasecmp((string) ($existing->json()[0]['event_type'] ?? ''), 'First Communion') !== 0) {
                return $back('That First Communion could not be found. It may have been removed.');
            }
        }

        $start = self::minutesOf($data['start']);
        $end   = self::minutesOf($data['end']);
        if ($start === null || $end === null || $end <= $start) {
            return $back('The end time must be after the start time.');
        }

        // Any active reservation whose time overlaps blocks it ("To be announced" never does).
        $sameDay = $this->sb()->get($this->sbUrl('reservations', [
            'select'           => 'id,event_type,reservation_time',
            'reservation_date' => 'eq.' . $data['date'],
            'status'           => 'in.(pending,approved,booked)',
        ]));
        foreach ($sameDay->successful() ? ($sameDay->json() ?? []) : [] as $r) {
            if ($editId && (int) ($r['id'] ?? 0) === $editId) continue;   // its own old time
            [$rs, $re] = self::rangeOf((string) ($r['reservation_time'] ?? ''));
            if ($rs !== null && $rs < $end && $re > $start) {
                return $back('That time overlaps a ' . ($r['event_type'] ?? 'reservation') . ' at '
                    . str_replace(' - ', ' – ', $r['reservation_time']) . '. Please choose another time.');
            }
        }

        $time = $data['start'] . ' - ' . $data['end'];
        $fields = [
            'reservation_date' => $data['date'],
            'reservation_time' => $time,
            'name'             => $data['school'],
            'phone'            => $data['phone'] ?? null,
            'notes'            => $data['notes'] ?? null,
            'details'          => ['communion' => [
                'school'    => $data['school'],
                'catechist' => $data['catechist'],
                'children'  => (int) $data['children'],
            ]],
        ];
        $insert = $editId
            ? $this->sb()->withHeaders(['Prefer' => 'return=minimal'])
                ->patch($this->sbUrl('reservations', ['id' => 'eq.' . $editId]), $fields + ['updated_at' => now()->toISOString()])
            : $this->sb()->withHeaders(['Prefer' => 'return=minimal'])->post($this->sbUrl('reservations'), $fields + [
                'event_type' => 'First Communion',
                'status'     => 'approved',
                'admin_note' => 'Added by the parish office',
            ]);
        if ($insert->failed()) {
            $msg = (string) ($insert->json('message') ?? '');
            return $back(str_starts_with($msg, 'SLOT_CONFLICT:') ? trim(substr($msg, 14)) : 'Could not save the First Communion. Please try again.');
        }

        return redirect()->route('admin.index', ['section' => 'schedule'])->with([
            'status_update_type'    => 'approved',
            'status_update_title'   => $editId ? 'First Communion Updated' : 'First Communion Added',
            'status_update_success' => 'First Communion for ' . $data['school'] . ($editId ? ' is now on ' : ' added on ')
                . \Carbon\Carbon::parse($data['date'])->format('M j, Y') . ', ' . str_replace(' - ', ' – ', $time) . '.',
        ]);
    }

    /** Report key of a reservation's event type, e.g. "First Communion" → "communion". */
    public static function typeKey(?string $eventType): ?string
    {
        $t = strtolower(trim((string) $eventType));
        return $t === 'first communion' ? 'communion' : (in_array($t, ['baptism', 'wedding', 'funeral', 'kumpil'], true) ? $t : null);
    }

    /** "9:30 AM" → 570 minutes after midnight, or null. */
    private static function minutesOf(string $time): ?int
    {
        if (!preg_match('/(\d{1,2}):(\d{2})\s*([AaPp][Mm])/', $time, $m)) return null;
        return ((int) $m[1] % 12 + (strtoupper($m[3]) === 'PM' ? 12 : 0)) * 60 + (int) $m[2];
    }

    /** Start/end minutes of a reservation time; a single time counts as one hour. */
    private static function rangeOf(string $time): array
    {
        $parts = preg_split('/\s*[-–]\s*/u', trim($time));
        $start = self::minutesOf($parts[0] ?? '');
        if ($start === null) return [null, null];
        $end = isset($parts[1]) ? self::minutesOf($parts[1]) : null;
        return [$start, $end && $end > $start ? $end : $start + 60];
    }

    /**
     * View details: staff tick a wedding document as received at the office
     * (or undo it). Answers with the new requirement status as JSON.
     */
    public function markDocumentReceived(Request $request, string $id)
    {
        if (!$this->isLoggedIn()) {
            return response()->json(['message' => 'Please log in again.'], 401);
        }
        $docs = \App\Support\ReservationRequirements::weddingDocuments();
        $key = (string) $request->input('document');
        if (!isset($docs[$key])) {
            return response()->json(['message' => 'Unknown document.'], 422);
        }

        $res = $this->sb()->get($this->sbUrl('reservations', [
            'id' => 'eq.' . (int) $id, 'select' => 'id,event_type,details,reservation_attachments(field_key)', 'limit' => 1,
        ]));
        $reservation = $res->successful() ? ($res->json()[0] ?? null) : null;
        $details = $reservation['details'] ?? [];
        if (is_string($details)) $details = json_decode($details, true) ?? [];
        if (!$reservation || !\App\Support\ReservationRequirements::usesFollowList($reservation['event_type'] ?? '', $details)) {
            return response()->json(['message' => 'This reservation has no documents to follow.'], 404);
        }

        $received = array_values(array_diff((array) ($details['wedding']['documents_received'] ?? []), [$key]));
        if ($request->boolean('received')) $received[] = $key;
        $details['wedding']['documents_received'] = $received;

        $patch = $this->sb()->withHeaders(['Prefer' => 'return=minimal'])
            ->patch($this->sbUrl('reservations', ['id' => 'eq.' . (int) $id]), [
                'details'    => $details,
                'updated_at' => now()->toISOString(),
            ]);
        if ($patch->failed()) {
            return response()->json(['message' => 'Could not save. Please try again.'], 500);
        }

        return response()->json([
            'requirements' => \App\Support\ReservationRequirements::status(
                $reservation['event_type'], $reservation['reservation_attachments'] ?? [], $details
            ),
        ]);
    }

    private function emptyViewData(string $section): array
    {
        $empty   = ['pending'=>[],'approved'=>[],'declined'=>[]];
        $zero    = ['total'=>0,'pending'=>0,'approved'=>0,'declined'=>0];
        return [
            'section' => $section, 'isLoggedIn' => false, 'adminRole' => 'admin',
            'reservations' => [], 'filteredReservations' => [],
            'grouped' => $empty, 'filteredGrouped' => $empty,
            'summaryTotals' => $zero, 'filteredTotals' => $zero,
            'reservationCountSummary' => '0 reservations',
            'reservationHeaderTotals' => $zero,
            'reservationFilterRange' => 'all', 'reservationFilterDate' => null, 'reservationFilterType' => 'all',
            'reservationFilterDesc' => 'Showing all reservations.', 'hasActiveFilter' => false, 'reservationSort' => 'latest',
            'cancelFilterActive' => false, 'cancelRequestCount' => 0,
            'announcements' => [], 'announcementCount' => 0, 'visibleAnnouncementCount' => 0,
            'recentReservations' => [], 'recentAnnouncements' => [],
            'scheduleDateValue' => now()->format('Y-m-d'),
            'scheduleDateHeading' => 'Today · ' . now()->format('F j, Y'),
            'scheduleDateDescription' => now()->format('l, F j, Y'),
            'dailySchedule' => [], 'scheduleEventCounts' => [], 'scheduleReservationCount' => 0,
            'scheduleCalendarJson' => [],
            'scheduleTodayCount' => 0, 'scheduleWeekCount' => 0, 'scheduleMonthCount' => 0, 'scheduleUpcoming' => [],
            'eventCounts' => ['Wedding'=>0,'Baptism'=>0,'Funeral'=>0],
            'customers' => [], 'customerSearch' => '', 'customerStatus' => 'all',
            'reportYear' => now()->year, 'reportMonth' => 0, 'reportType' => 'all',
            'reportFromYear' => now()->year, 'reportFromMonth' => 1, 'reportToYear' => now()->year, 'reportToMonth' => now()->month,
            'reportRangeLabel' => 'January ' . now()->year . ' – ' . now()->format('F') . ' ' . now()->year,
            'reportMonthlyData' => [],
            'reportTotals' => ['baptism'=>0,'wedding'=>0,'funeral'=>0,'kumpil'=>0,'communion'=>0,'total'=>0,'approved'=>0,'pending'=>0,'declined'=>0],
            'reportDayData' => [], 'reportSummary' => null,
            'donationMonthly' => [], 'donationTotal' => 0, 'recentDonations' => [],
            'utilizationMonthly' => [],
            'pastApproved' => [], 'attendanceLookup' => [], 'attendanceAvg' => ['Wedding'=>0,'Baptism'=>0,'Funeral'=>0],
            'donationsList' => [], 'donationSearch' => '', 'donationPurpose' => 'all',
            'donationFrom' => '', 'donationTo' => '',
            'donationSectionTotal' => 0,
            'donationsByPurpose' => ['building_fund'=>0,'mass_intention'=>0,'tithes'=>0,'other'=>0],
            'reportRecommendations' => [],
            'priests' => [], 'officiantWorkload' => [], 'editCommunion' => null,
        ];
    }

    private function groupByStatus(array $reservations): array
    {
        $groups = ['pending'=>[],'approved'=>[],'declined'=>[]];
        foreach ($reservations as $r) {
            $s = strtolower((string)($r['status'] ?? 'pending'));
            // cancelled reservations are listed with the declined ones (badge says Cancelled)
            if ($s === 'cancelled') $s = 'declined';
            if (!isset($groups[$s])) $groups[$s] = [];
            $groups[$s][] = $r;
        }
        return $groups;
    }

    private function filterByDateWindow(array $reservations, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        return array_values(array_filter($reservations, function ($r) use ($start, $end) {
            $raw = $r['preferred_date'] ?? $r['reservation_date'] ?? null;
            if (!$raw) return false;
            try {
                $d = (new DateTimeImmutable((string)$raw))->setTime(0,0,0);
                return $d >= $start && $d <= $end;
            } catch (\Exception) { return false; }
        }));
    }

    private function applyReservationTimeFilter(array $reservations, ?string $rangeInput, ?string $dateInput): array
    {
        $allowed  = ['all','today','week','date'];
        $range    = in_array(strtolower(trim((string)$rangeInput)), $allowed) ? strtolower(trim((string)$rangeInput)) : 'all';
        $date     = null;
        $desc     = 'Showing all reservations.';
        $filtered = $reservations;

        if ($range === 'today') {
            $start    = (new DateTimeImmutable('today'))->setTime(0,0,0);
            $filtered = $this->filterByDateWindow($reservations, $start, $start->setTime(23,59,59));
            $desc     = 'Showing reservations for today (' . $start->format('M j, Y') . ').';
        } elseif ($range === 'week') {
            $today    = new DateTimeImmutable('today');
            $start    = $today->modify('monday this week')->setTime(0,0,0);
            $end      = $today->modify('sunday this week')->setTime(23,59,59);
            $filtered = $this->filterByDateWindow($reservations, $start, $end);
            $desc     = 'Showing reservations for this week (' . $start->format('M j') . ' – ' . $end->format('M j, Y') . ').';
        } elseif ($range === 'date') {
            $trimmed  = trim((string)$dateInput);
            $selected = $trimmed ? DateTimeImmutable::createFromFormat('Y-m-d', $trimmed) : false;
            if ($selected) {
                $filtered = $this->filterByDateWindow($reservations, $selected->setTime(0,0,0), $selected->setTime(23,59,59));
                $date     = $selected->format('Y-m-d');
                $desc     = 'Showing reservations for ' . $selected->format('M j, Y') . '.';
            } else {
                $range = 'all';
            }
        }

        return ['reservations' => $filtered, 'range' => $range, 'date' => $date, 'description' => $desc];
    }

    private function timeSortValue(?string $time): int
    {
        if (!$time || trim($time) === '') return 86400;
        $candidate = trim($time);
        if (preg_match('/[\-–—]/u', $candidate)) {
            $parts = preg_split('/\s*[\-–—]\s*/u', $candidate);
            if (is_array($parts) && isset($parts[0]) && trim($parts[0]) !== '') $candidate = trim($parts[0]);
        }
        $ts = strtotime($candidate);
        if ($ts !== false) {
            return ((int)date('G', $ts)) * 3600 + ((int)date('i', $ts)) * 60 + (int)date('s', $ts);
        }
        return 86400;
    }
}
