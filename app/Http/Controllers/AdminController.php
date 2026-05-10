<?php

namespace App\Http\Controllers;

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
            'apikey'        => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
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
        $allowed = ['overview', 'reservations', 'schedule', 'announcements', 'analytics', 'customers'];
        if ($section === null) return self::DEFAULT_SECTION;
        $normalized = strtolower(trim($section));
        return in_array($normalized, $allowed) ? $normalized : self::DEFAULT_SECTION;
    }

    private function isLoggedIn(): bool
    {
        return Session::get('admin_logged_in') === true;
    }

    private function validateLogin(string $username, string $password): bool
    {
        return $username === config('services.admin.username')
            && $password === config('services.admin.password');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('admin.index');
    }

    public function handle(Request $request)
    {
        $action          = $request->input('action');
        $redirectSection = $this->sanitizeSection($request->input('redirect_section'));

        // LOGIN
        if ($action === 'login') {
            $username = trim($request->input('username', ''));
            $password = $request->input('password', '');
            if ($this->validateLogin($username, $password)) {
                Session::put('admin_logged_in', true);
                Session::put('admin_username', $username);
                return redirect()->route('admin.index', ['section' => $redirectSection]);
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
                    $note = trim($request->input('admin_note', ''));
                    $adminNote = $note !== ''
                        ? $note
                        : ($status === 'approved' ? 'Approved by admin' : ($status === 'declined' ? 'Declined by admin' : 'Marked as pending'));

                    $this->sb()->patch($this->sbUrl('reservations', ['id' => 'eq.' . $id]), [
                        'status'     => $status,
                        'admin_note' => $adminNote,
                        'updated_at' => now()->toISOString(),
                    ]);

                    $details = $reservation['details'] ?? [];
                    if (is_string($details)) $details = json_decode($details, true) ?? [];

                    $phone = $reservation['phone'] ?? ($details['phone'] ?? null);
                    $name  = $reservation['name']  ?? ($details['name']  ?? 'Customer');
                    $email = $reservation['email'] ?? null;

                    if (!empty($email)) {
                        Mail::send('emails.reservation-status', [
                            'name'             => $name,
                            'status'           => $status,
                            'event_type'       => $reservation['event_type'] ?? '',
                            'reservation_date' => $reservation['reservation_date'] ?? '',
                            'reservation_time' => $reservation['reservation_time'] ?? '',
                            'admin_note'       => $adminNote,
                        ], function ($message) use ($email, $status) {
                            $message->to($email)->subject(match ($status) {
                                'approved' => 'Your reservation has been approved',
                                'declined' => 'Update on your reservation request',
                                default    => 'Reservation status update',
                            });
                        });
                    }

                    // SMS_DISABLED — re-enable when SMS is restored
                    // if (!empty($phone)) {
                    //     app(\App\Services\SmsNotificationService::class)->send(
                    //         $phone,
                    //         match ($status) {
                    //             'approved' => "Good news {$name}! Your reservation has been APPROVED.",
                    //             'declined' => "Hello {$name}, your reservation was DECLINED. Please contact the parish.",
                    //             default    => "Hello {$name}, your reservation status is now {$status}.",
                    //         }
                    //     );
                    // }
                }
                Session::flash('flash_success', 'Reservation status updated successfully.');
            } catch (\Throwable $e) {
                Session::flash('flash_error', $e->getMessage());
            }
            return redirect()->route('admin.index', ['section' => 'reservations']);
        }

        // CREATE ANNOUNCEMENT
        if ($action === 'create_announcement') {
            $title = trim($request->input('announcement_title', ''));
            $body  = trim($request->input('announcement_body', ''));
            $show  = $request->input('announcement_show') === '1' ? 1 : 0;

            if ($title === '' || $body === '') {
                Session::flash('flash_error', 'Please provide both a title and message.');
                return redirect()->route('admin.index', ['section' => 'announcements']);
            }

            $path = null;

if ($request->hasFile('announcement_image')) {
    $file = $request->file('announcement_image');

    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
    $contents = file_get_contents($file->getRealPath());

    $upload = \Illuminate\Support\Facades\Http::withBody(
        $contents,
        $file->getMimeType()
    )->withHeaders([
        'apikey' => config('services.supabase.key'),
        'Authorization' => 'Bearer ' . config('services.supabase.key'),
    ])->put(
        rtrim(config('services.supabase.url'), '/') . '/storage/v1/object/announcements/' . $filename
    );

    if (!$upload->successful()) {
        Session::flash('flash_error', 'Image upload failed: ' . $upload->body());
        return redirect()->route('admin.index', ['section' => 'announcements']);
    }

    $path = 'announcements/' . $filename;
}

            $insert = $this->sb()->post($this->sbUrl('announcements'), [
                'title'        => $title,
                'body'         => $body,
                'image_path'   => $path,
                'show_on_home' => (bool) $show,
                'created_at'   => now()->toISOString(),
            ]);
            if ($insert->successful()) {
                Session::flash('flash_success', 'Announcement published successfully.');
            } else {
                Session::flash('flash_error', 'Failed to publish announcement: ' . $insert->body());
            }
            return redirect()->route('admin.index', ['section' => 'announcements']);
        }

        // TOGGLE ANNOUNCEMENT
        if ($action === 'toggle_announcement') {
            $id   = (int) $request->input('announcement_id');
            $show = $request->input('show_on_home') === '1';
            $upd  = $this->sb()->patch($this->sbUrl('announcements', ['id' => 'eq.' . $id]), ['show_on_home' => $show]);
            Session::flash($upd->successful() ? 'flash_success' : 'flash_error',
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
            Session::flash($del->successful() ? 'flash_success' : 'flash_error',
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
        Session::flash('flash_error', 'Customer not found.');
        return redirect()->route('admin.index', ['section' => 'customers']);
    }

    $this->sb()->patch($this->sbUrl('customers', ['id' => 'eq.' . $id]), [
        'status'          => $status,
        'account_status'  => $status,
        'disabled_reason' => $status === 'disabled' ? $reason : null,
    ]);

$emailSent = false;

if (empty($customer['email'])) {
    Session::flash('flash_error', 'Customer status updated, but no email was sent because this customer has no email address.');
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
        Session::flash('flash_error', 'Customer status updated, but email failed: ' . $e->getMessage());
        \Illuminate\Support\Facades\Log::error('Customer status email failed: ' . $e->getMessage());
    }
}

if ($emailSent) {
    Session::flash('flash_success', 'Customer status updated and email sent to ' . $customer['email'] . '.');
} elseif (!session('flash_error')) {
    Session::flash('flash_success', 'Customer status updated.');
}
    return redirect()->route('admin.index', ['section' => 'customers']);
}

if ($action === 'reset_password') {
    $id = (int) $request->input('customer_id');

    $custRes  = $this->sb()->get($this->sbUrl('customers', ['id' => 'eq.' . $id, 'select' => 'id,name,email', 'limit' => 1]));
    $customer = $custRes->successful() ? ($custRes->json()[0] ?? null) : null;

    if (!$customer || empty($customer['email'])) {
        Session::flash('flash_error', 'Customer email not found.');
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

    Mail::raw(
        "Hello {$customer['name']},\n\nThe administrator requested a password reset for your account.\n\nClick this link to reset your password:\n{$resetUrl}\n\nThis link will expire in 1 hour.\n\nSt. John the Baptist Parish",
        function ($message) use ($customer) {
            $message->to($customer['email'])
                ->subject('Reset your St. John the Baptist Parish password');
        }
    );

    $this->sb()->patch($this->sbUrl('customers', ['id' => 'eq.' . $customer['id']]), [
        'password_reset_required' => true,
        'last_password_reset_at'  => now()->toISOString(),
    ]);

    Session::flash('flash_success', 'Password reset email sent.');
    return redirect()->route('admin.index', ['section' => 'customers']);
}

if ($action === 'delete_customer') {
    $id = (int) $request->input('customer_id');

    $custRes  = $this->sb()->get($this->sbUrl('customers', ['id' => 'eq.' . $id, 'select' => 'id', 'limit' => 1]));
    $customer = $custRes->successful() ? ($custRes->json()[0] ?? null) : null;

    if (!$customer) {
        Session::flash('flash_error', 'Customer not found.');
        return redirect()->route('admin.index', ['section' => 'customers']);
    }

    $resRes          = $this->sb()->get($this->sbUrl('reservations', ['customer_id' => 'eq.' . $id, 'select' => 'id', 'limit' => 1]));
    $hasReservations = $resRes->successful() && !empty($resRes->json());

    if ($hasReservations) {
        Session::flash('flash_error', 'This customer cannot be deleted because they have reservation records.');
        return redirect()->route('admin.index', ['section' => 'customers']);
    }

    $this->sb()->delete($this->sbUrl('customer_password_resets', ['customer_id' => 'eq.' . $id]));
    $this->sb()->delete($this->sbUrl('customers', ['id' => 'eq.' . $id]));

    Session::flash('flash_success', 'Customer profile deleted successfully.');
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

        // ===================== CUSTOMERS =====================
$customers = [];
$customerSearch = trim((string) $request->input('customer_search', ''));
$customerStatus = trim((string) $request->input('customer_status', 'all'));

try {
    $url = rtrim(config('services.supabase.url'), '/');
    $key = config('services.supabase.key');

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
            'select' => '*',
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
        $r['attachments'] = [];

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
        $hasActiveFilter        = $reservationFilterRange !== 'all';

        $grouped         = $this->groupByStatus($reservations);
        $filteredGrouped = $this->groupByStatus($filteredReservations);

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
];

foreach ($reservations as $r) {
    $type = strtolower(trim($r['event_type'] ?? ''));
    $status = strtolower(trim($r['status'] ?? ''));

    if ($status !== 'approved') continue;

    if ($type === 'wedding') $eventCounts['Wedding']++;
    if ($type === 'baptism') $eventCounts['Baptism']++;
    if ($type === 'funeral') $eventCounts['Funeral']++;
}

        $reservationCountSummary = sprintf('%d of %d reservations', $filteredTotals['total'], $summaryTotals['total']);
        $reservationHeaderTotals = $section === 'reservations' ? $filteredTotals : $summaryTotals;

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

        $scheduleCalendarJson = [];

foreach ($scheduleReservations as $r) {
    $date = $r['preferred_date'] ?? $r['reservation_date'] ?? null;

    if (!$date) {
        continue;
    }

    $dateKey = (new DateTimeImmutable((string) $date))->format('Y-m-d');
    $reservationDate = new DateTimeImmutable($dateKey);
    $isDone = $reservationDate < $today;

    $scheduleCalendarJson[$dateKey][] = [
        'id' => $r['id'] ?? null,
        'name' => $r['name'] ?? $r['details']['name'] ?? 'No name provided',
        'email' => $r['email'] ?? $r['details']['email'] ?? 'Not provided',
        'phone' => $r['phone'] ?? $r['details']['phone'] ?? 'Not provided',
        'eventType' => $r['event_type'] ?? 'Unspecified',
        'time' => $r['preferred_time'] ?? $r['reservation_time'] ?? '—',
        'date' => $dateKey,
        'status' => strtolower((string)($r['status'] ?? 'pending')),
        'statusLabel' => $isDone ? 'Done' : 'Upcoming',
    ];
}

        // Announcements
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
        $announcementCount        = count($announcements);
        $visibleAnnouncementCount = count(array_filter($announcements, fn($a) => (int)($a->show_on_home ?? 0) === 1));

       // Analytics
$baptismChart = ['labels'=>[],'totals'=>[]];
$weddingChart = ['labels'=>[],'totals'=>[]];
$funeralChart = ['labels'=>[],'totals'=>[]];

$baptismForecast = 0;
$weddingForecast = 0;
$funeralForecast = 0;

$baptismPeak = null;
$weddingPeak = null;
$funeralPeak = null;

// Analytics
$baptismChart = ['labels'=>[],'totals'=>[]];
$weddingChart = ['labels'=>[],'totals'=>[]];
$funeralChart = ['labels'=>[],'totals'=>[]];

$baptismForecast = 0;
$weddingForecast = 0;
$funeralForecast = 0;

$baptismPeak = null;
$weddingPeak = null;
$funeralPeak = null;

$buildChart = function (array $reservations, string $eventType) {
    $monthly = [];

    foreach ($reservations as $r) {
        $type = strtolower(trim($r['event_type'] ?? ''));
        $status = strtolower(trim($r['status'] ?? ''));
        $date = $r['reservation_date'] ?? null;

        if ($type !== strtolower($eventType)) continue;
        if (!in_array($status, ['approved', 'pending'])) continue;
        if (!$date) continue;

        $monthKey = date('Y-m', strtotime($date));
        $label = date('M Y', strtotime($date));

        if (!isset($monthly[$monthKey])) {
            $monthly[$monthKey] = [
                'label' => $label,
                'total' => 0,
            ];
        }

        $monthly[$monthKey]['total']++;
    }

    ksort($monthly);

    return [
        'labels' => array_values(array_column($monthly, 'label')),
        'totals' => array_values(array_column($monthly, 'total')),
    ];
};

$forecast = function (array $chart) {
    $totals = $chart['totals'] ?? [];

    if (count($totals) === 0) {
        return 0;
    }

    $lastThree = array_slice($totals, -3);

    return (int) round(array_sum($lastThree) / count($lastThree));
};

$peak = function (array $chart) {
    if (empty($chart['totals'])) {
        return null;
    }

    $max = max($chart['totals']);
    $index = array_search($max, $chart['totals']);

    return [
        'label' => $chart['labels'][$index] ?? '',
        'total' => $max,
    ];
};

$baptismChart = $buildChart($reservations, 'Baptism');
$weddingChart = $buildChart($reservations, 'Wedding');
$funeralChart = $buildChart($reservations, 'Funeral');

$baptismForecast = $forecast($baptismChart);
$weddingForecast = $forecast($weddingChart);
$funeralForecast = $forecast($funeralChart);

$baptismPeak = $peak($baptismChart);
$weddingPeak = $peak($weddingChart);
$funeralPeak = $peak($funeralChart);

        $recentReservations  = array_slice($reservations, 0, 5);
        $recentAnnouncements = array_slice($announcements, 0, 3);

        return view('admin.index', compact(
            'section', 'isLoggedIn',
            'reservations', 'filteredReservations',
            'grouped', 'filteredGrouped',
            'summaryTotals', 'filteredTotals',
            'reservationCountSummary', 'reservationHeaderTotals',
            'reservationFilterRange', 'reservationFilterDate',
            'reservationFilterDesc', 'hasActiveFilter',
            'announcements', 'announcementCount', 'visibleAnnouncementCount',
            'recentReservations', 'recentAnnouncements',
            'scheduleDateValue', 'scheduleDateHeading', 'scheduleDateDescription',
            'dailySchedule', 'scheduleEventCounts', 'scheduleReservationCount',
            'baptismChart', 'weddingChart', 'funeralChart',
'baptismForecast', 'weddingForecast', 'funeralForecast',
'baptismPeak', 'weddingPeak', 'funeralPeak',
            'scheduleCalendarJson',
            'eventCounts',
            'customers',
            'customerSearch',
'customerStatus',
        ));
    }

    // ── Private helpers ───────────────────────────────────────────────

    private function emptyViewData(string $section): array
    {
        $empty   = ['pending'=>[],'approved'=>[],'declined'=>[]];
        $zero    = ['total'=>0,'pending'=>0,'approved'=>0,'declined'=>0];
        $noChart = ['labels'=>[],'totals'=>[]];
        return [
            'section' => $section, 'isLoggedIn' => false,
            'reservations' => [], 'filteredReservations' => [],
            'grouped' => $empty, 'filteredGrouped' => $empty,
            'summaryTotals' => $zero, 'filteredTotals' => $zero,
            'reservationCountSummary' => '0 reservations',
            'reservationHeaderTotals' => $zero,
            'reservationFilterRange' => 'all', 'reservationFilterDate' => null,
            'reservationFilterDesc' => 'Showing all reservations.', 'hasActiveFilter' => false,
            'announcements' => [], 'announcementCount' => 0, 'visibleAnnouncementCount' => 0,
            'recentReservations' => [], 'recentAnnouncements' => [],
            'scheduleDateValue' => now()->format('Y-m-d'),
            'scheduleDateHeading' => 'Today · ' . now()->format('F j, Y'),
            'scheduleDateDescription' => now()->format('l, F j, Y'),
            'dailySchedule' => [], 'scheduleEventCounts' => [], 'scheduleReservationCount' => 0,
            'scheduleCalendarJson' => [],
            'eventCounts' => ['Wedding'=>0,'Baptism'=>0,'Funeral'=>0],
            'baptismChart' => $noChart, 'weddingChart' => $noChart, 'funeralChart' => $noChart,
            'baptismForecast' => 0, 'weddingForecast' => 0, 'funeralForecast' => 0,
            'baptismPeak' => null, 'weddingPeak' => null, 'funeralPeak' => null,
            'customers' => [], 'customerSearch' => '', 'customerStatus' => 'all',
        ];
    }

    private function groupByStatus(array $reservations): array
    {
        $groups = ['pending'=>[],'approved'=>[],'declined'=>[]];
        foreach ($reservations as $r) {
            $s = strtolower((string)($r['status'] ?? 'pending'));
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
