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

        // CHECK IF SAME DATE + SAME EVENT TYPE + SAME TIME IS ALREADY TAKEN
    $existingReservationsResponse = Http::withHeaders([
        'apikey' => config('services.supabase.key'),
        'Authorization' => 'Bearer ' . config('services.supabase.key'),
    ])->get(config('services.supabase.url') . '/rest/v1/reservations', [
        'select' => 'id,event_type,reservation_date,reservation_time,status',
        'reservation_date' => 'eq.' . $validated['reservation_date'],
        'event_type' => 'eq.' . $validated['event_type'],
        'status' => 'in.(approved,pending,booked)',
    ]);

    $existingReservations = $existingReservationsResponse->json() ?? [];

    // BAPTISM LIMIT: ONLY 5 BOOKINGS PER DATE
    if (strtolower($validated['event_type']) === 'baptism') {
        if (count($existingReservations) >= 5) {
            return back()->withInput()->withErrors([
                'reservation' => 'Baptism booking limit reached for this date. Please choose another date.',
            ]);
        }
    }

    // SAME TIME IS NOT ALLOWED IF ALREADY APPROVED/PENDING
    $timeAlreadyTaken = collect($existingReservations)->contains(function ($reservation) use ($validated) {
        $existingTime = trim(str_replace('–', '-', $reservation['reservation_time'] ?? ''));
        $selectedTime = trim(str_replace('–', '-', $validated['reservation_time']));

        return strtolower($existingTime) === strtolower($selectedTime);
    });

    if ($timeAlreadyTaken) {
        return back()->withInput()->withErrors([
            'reservation' => 'This time slot is already taken or pending. Please choose another available time.',
        ]);
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