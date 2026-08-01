<?php

namespace App\Http\Controllers;

use App\Mail\ReservationSubmitted;
use App\Mail\ReservationCustomerConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class ReservationController extends Controller
{
    public function index()
    {
        $approvedReservations = Http::withHeaders([
            'apikey'        => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
        ])->get(config('services.supabase.url') . '/rest/v1/reservations', [
            'select'           => '*',
            'status'           => 'in.(approved,pending)',
            'reservation_date' => 'gte.' . now()->toDateString(),
            'order'            => 'reservation_date.asc',
        ])->json();

        $grouped = collect($approvedReservations ?? [])
            ->groupBy('reservation_date')
            ->map(function ($items) {
                return [
                    'date'         => $items->first()['reservation_date'] ?? '',
                    'reservations' => $items->map(function ($r) {
                        $details = $r['details'] ?? [];
                        if (is_string($details)) {
                            $details = json_decode($details, true) ?? [];
                        }
                        return [
                            'name'          => $details['name'] ?? '',
                            'eventType'     => $r['event_type'] ?? '',
                            'preferredTime' => trim(str_replace('–', '-', $r['reservation_time'] ?? '')),
                            'status'        => strtolower($r['status'] ?? 'pending'),
                        ];
                    })->values(),
                ];
            })
            ->values();

        return view('reservation.index', [
            'customer'             => null,
            'approvedReservations' => $grouped,
        ]);
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:50',
        'event_type' => 'required|string',
        'reservation_date' => 'required|date',
        'reservation_time' => 'required|string|max:100',
        'notes' => 'nullable|string',
        'baptism_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'wedding_file1' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'wedding_file2' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'funeral_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ], [
        'name.required' => 'Please enter the name of the person reserving.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'phone.required' => 'Please enter your contact number.',
        'event_type.required' => 'Please choose an event type.',
        'reservation_date.required' => 'Please select a reservation date.',
        'reservation_time.required' => 'Please select a preferred time.',
    ]);

    // DUPLICATE ENTRY CHECK: same customer email + same event type + same date
    $dupResponse = Http::withHeaders([
        'apikey'        => config('services.supabase.key'),
        'Authorization' => 'Bearer ' . config('services.supabase.key'),
    ])->get(config('services.supabase.url') . '/rest/v1/reservations', [
        'select'           => 'id,status',
        'email'            => 'eq.' . $validated['email'],
        'event_type'       => 'eq.' . $validated['event_type'],
        'reservation_date' => 'eq.' . $validated['reservation_date'],
        'status'           => 'in.(approved,pending,booked)',
    ]);

    if ($dupResponse->successful() && !empty($dupResponse->json())) {
        $dupStatus = ucfirst($dupResponse->json()[0]['status'] ?? 'pending');
        return back()->withInput()->withErrors([
            'reservation' => "You already have a {$dupStatus} {$validated['event_type']} reservation on this date. Please contact the parish office if you need to make changes.",
        ]);
    }

    $files = [];

    foreach ([
        'baptism_file' => 'Baptism requirement',
        'wedding_file1' => 'Wedding requirement 1',
        'wedding_file2' => 'Wedding requirement 2',
        'funeral_file' => 'Funeral requirement',
    ] as $field => $label) {
        if ($request->hasFile($field)) {
          $file = $request->file($field);

$fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

$bucket = 'reservation-attachments';

$upload = Http::withHeaders([
    'apikey' => config('services.supabase.key'),
    'Authorization' => 'Bearer ' . config('services.supabase.key'),
    'Content-Type' => $file->getMimeType(),
])->withBody(
    file_get_contents($file->getRealPath()),
    $file->getMimeType()
)->post(
    config('services.supabase.url') . "/storage/v1/object/{$bucket}/{$fileName}"
);

if (!$upload->successful()) {
    return back()->withErrors([
        'upload' => 'File upload failed: ' . $upload->body()
    ]);
}

$fileUrl = config('services.supabase.url') .
    "/storage/v1/object/public/{$bucket}/{$fileName}";

$files[] = [
    'field_key' => $field,
    'label' => $label,
    'file_url' => $fileUrl,
];
        }
    }

        // CONFLICT CHECK: fetch all reservations on the same date, any event type
        $allSameDateResponse = Http::withHeaders([
            'apikey'        => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
        ])->get(config('services.supabase.url') . '/rest/v1/reservations', [
            'select'           => 'id,event_type,reservation_date,reservation_time,status',
            'reservation_date' => 'eq.' . $validated['reservation_date'],
            'status'           => 'in.(approved,pending,booked)',
        ]);

        $allSameDate  = collect($allSameDateResponse->json() ?? []);
        $isBaptism    = strtolower($validated['event_type']) === 'baptism';
        $selectedTime = strtolower(trim(str_replace('–', '-', $validated['reservation_time'])));

        // All reservations at the exact same time slot (any type)
        $sameTimeSlot = $allSameDate->filter(function ($r) use ($selectedTime) {
            $existing = strtolower(trim(str_replace('–', '-', $r['reservation_time'] ?? '')));
            return $existing === $selectedTime;
        });

        if ($isBaptism) {
            // Baptisms are group ceremonies — allow up to 5 per date at the same slot
            $baptismsAtTime = $sameTimeSlot->filter(fn($r) => strtolower($r['event_type'] ?? '') === 'baptism');
            if ($baptismsAtTime->count() >= 5) {
                return back()->withInput()->withErrors([
                    'reservation' => 'Baptism slots for this time are full (max 5). Please choose another date.',
                ]);
            }
            // Block if another event type (Wedding/Funeral) is already using the same slot
            $otherAtTime = $sameTimeSlot->filter(fn($r) => strtolower($r['event_type'] ?? '') !== 'baptism');
            if ($otherAtTime->isNotEmpty()) {
                return back()->withInput()->withErrors([
                    'reservation' => 'This time slot is already reserved for another event. Please choose a different time.',
                ]);
            }
        } else {
            // Wedding/Funeral: the church holds one ceremony at a time — block any overlap
            if ($sameTimeSlot->isNotEmpty()) {
                $conflictType = ucfirst($sameTimeSlot->first()['event_type'] ?? 'event');
                return back()->withInput()->withErrors([
                    'reservation' => "This time slot already has a {$conflictType} reservation. Please choose another available time.",
                ]);
            }
        }

  $customerId = null;

$customerResponse = Http::withHeaders([
    'apikey' => config('services.supabase.key'),
    'Authorization' => 'Bearer ' . config('services.supabase.key'),
])->get(config('services.supabase.url') . '/rest/v1/customers', [
    'select' => 'id',
    'email' => 'eq.' . $validated['email'],
    'limit' => 1,
]);

if ($customerResponse->successful() && !empty($customerResponse->json())) {
    $customerId = $customerResponse->json()[0]['id'];
}

    $response = Http::withHeaders([
        'apikey' => config('services.supabase.key'),
        'Authorization' => 'Bearer ' . config('services.supabase.key'),
        'Content-Type' => 'application/json',
        'Prefer' => 'return=representation',
   ])->post(config('services.supabase.url') . '/rest/v1/reservations', [
    'customer_id' => $customerId,
    'name' => $validated['name'],
    'email' => $validated['email'],
    'phone' => $validated['phone'],
    'notes' => $validated['notes'] ?? null,
    'event_type' => $validated['event_type'],
    'reservation_date' => $validated['reservation_date'],
    'reservation_time' => $validated['reservation_time'],
    'status' => 'pending',
    'details' => [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'notes' => $validated['notes'] ?? null,
    ],
]);

    if ($response->failed()) {
        return back()->withInput()->withErrors([
            'reservation' => 'Reservation failed: ' . $response->body(),
        ]);
    }

    $reservationId = $response->json()[0]['id'] ?? null;

    if (!$reservationId) {
        return back()->withInput()->withErrors([
            'reservation' => 'Reservation was saved but no ID was returned.',
        ]);
    }

    foreach ($files as $file) {
        Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->post(config('services.supabase.url') . '/rest/v1/reservation_attachments', [
            'reservation_id' => $reservationId,
            'file_url' => $file['file_url'],
            'field_key' => $file['field_key'],
            'label' => $file['label'],
        ]);
    }

    $mailData = $validated;

    try {
        Mail::to(config('services.admin.notification_email'))->send(
            new ReservationSubmitted($mailData, $files)
        );
    } catch (\Throwable $e) {
        Log::error('Admin reservation email failed: ' . $e->getMessage());
    }

    try {
        Mail::to($mailData['email'])->send(
            new ReservationCustomerConfirmation($mailData, $files)
        );

        //app(\App\Services\SmsNotificationService::class)->send(
    //$validated['phone'],
    //"Hi {$validated['name']}, your reservation has been submitted successfully. Please wait for parish confirmation."
//);

    } catch (\Throwable $e) {
        Log::error('Customer reservation confirmation email failed: ' . $e->getMessage());
    }

    return back()->with([
        'success' => 'Reservation submitted successfully!',
        'reservation_notifications' => [
            [
                'icon' => 'success',
                'title' => 'Reservation Submitted',
                'text' => 'Your reservation request has been submitted successfully. Please wait for parish confirmation.',
            ],
        ],
    ]);
 }

public function cancelRequest(Request $request, string $id)
{
    $customerId = session('customer_id');
    $reason     = trim($request->input('cancel_reason', ''));

    // Fetch the reservation and verify it belongs to this customer
    $res = Http::withHeaders([
        'apikey'        => config('services.supabase.service_role_key'),
        'Authorization' => 'Bearer ' . config('services.supabase.service_role_key'),
    ])->get(rtrim(config('services.supabase.url'), '/') . '/rest/v1/reservations', [
        'select' => 'id,customer_id,status,cancellation_requested,event_type,reservation_date,reservation_time,name,email',
        'id'     => 'eq.' . $id,
        'limit'  => 1,
    ]);

    $reservation = $res->successful() ? ($res->json()[0] ?? null) : null;

    if (!$reservation || (string)($reservation['customer_id'] ?? '') !== (string)$customerId) {
        return redirect()->route('reservation.my')->with('error', 'Reservation not found.');
    }

    $status = strtolower($reservation['status'] ?? '');

    if (!in_array($status, ['pending', 'approved'])) {
        return redirect()->route('reservation.my')->with('error', 'This reservation cannot be cancelled.');
    }

    if (!empty($reservation['cancellation_requested'])) {
        return redirect()->route('reservation.my')->with('error', 'A cancellation request is already pending.');
    }

    // Flag the reservation — use service role key to bypass RLS
    $patch = Http::withHeaders([
        'apikey'        => config('services.supabase.service_role_key'),
        'Authorization' => 'Bearer ' . config('services.supabase.service_role_key'),
        'Content-Type'  => 'application/json',
        'Prefer'        => 'return=minimal',
    ])->patch(rtrim(config('services.supabase.url'), '/') . '/rest/v1/reservations?id=eq.' . $id, [
        'cancellation_requested' => true,
        'cancel_reason'          => $reason ?: null,
        'updated_at'             => now()->toISOString(),
    ]);

    if ($patch->failed()) {
        return redirect()->route('reservation.my')->with('error',
            'Could not submit your request: ' . $patch->body()
        );
    }

    // Notify admin
    try {
        $adminEmail = config('services.admin.notification_email');
        if ($adminEmail) {
            $eventType = $reservation['event_type'] ?? 'reservation';
            $eventDate = $reservation['reservation_date'] ?? '—';
            Mail::send('emails.cancellation-request', [
                'customerName' => $reservation['name'] ?? 'A customer',
                'eventType'    => $eventType,
                'eventDate'    => $eventDate,
                'reason'       => $reason,
            ], function ($message) use ($adminEmail, $eventType, $eventDate) {
                $message->to($adminEmail)
                    ->subject('Cancellation Request: ' . ucfirst($eventType) . ' on ' . $eventDate);
            });
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Cancellation admin email failed: ' . $e->getMessage());
    }

    return redirect()->route('reservation.my')->with('success', 'Your cancellation request has been submitted. The parish office will respond shortly.');
}

public function myReservations()
{
    $customerId = session('customer_id');

    $response = Http::withHeaders([
        'apikey'        => config('services.supabase.key'),
        'Authorization' => 'Bearer ' . config('services.supabase.key'),
    ])->get(config('services.supabase.url') . '/rest/v1/reservations', [
        'select'      => 'id,event_type,reservation_date,reservation_time,status,admin_note,created_at,name',
        'customer_id' => 'eq.' . $customerId,
        'order'       => 'created_at.desc',
    ]);

    $reservations = $response->successful() ? ($response->json() ?? []) : [];

    return view('reservation.my-reservations', [
        'reservations'   => $reservations,
        'customerName'   => session('customer_name', 'Member'),
        'customerEmail'  => session('customer_email', ''),
    ]);
}

public function show(string $id)
{
    if (!session()->get('admin_logged_in')) {
        return redirect()->route('admin.index');
    }

    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'apikey' => config('services.supabase.key'),
        'Authorization' => 'Bearer ' . config('services.supabase.key'),
    ])->get(config('services.supabase.url') . '/rest/v1/reservations', [
        'select' => '*,reservation_attachments(*)',
        'id' => 'eq.' . $id,
        'limit' => 1,
    ]);

    if ($response->failed() || empty($response->json())) {
        return view('reservation.view', [
            'reservation' => null,
            'errorTitle' => 'Reservation not found',
            'errorMessage' => 'The reservation you are looking for may have been removed or no longer exists.',
        ]);
    }

    $reservation = $response->json()[0];

    if (isset($reservation['details']) && is_string($reservation['details'])) {
        $reservation['details'] = json_decode($reservation['details'], true) ?? [];
    }

    $reservation['name'] = $reservation['name'] ?? $reservation['details']['name'] ?? null;
    $reservation['email'] = $reservation['email'] ?? $reservation['details']['email'] ?? null;
    $reservation['phone'] = $reservation['phone'] ?? $reservation['details']['phone'] ?? null;
    $reservation['notes'] = $reservation['notes'] ?? $reservation['details']['notes'] ?? null;

    $reservation['preferred_date'] = $reservation['reservation_date'] ?? null;
    $reservation['preferred_time'] = $reservation['reservation_time'] ?? null;
    $reservation['attachments'] = $reservation['reservation_attachments'] ?? [];

    return view('reservation.view', [
        'reservation' => $reservation,
        'errorTitle' => null,
        'errorMessage' => null,
    ]);
}

}