<?php

namespace App\Http\Controllers;

use App\Services\DonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Customer Donations page: pay to the parish GCash account, then submit
 * the reference number and a screenshot for the staff to verify (panel
 * revision: GCash inclusion / online donation). Also used to pay for an
 * approved reservation from My Reservations.
 */
class DonationController extends Controller
{
    /** Suggested amounts shown when paying for a reservation (from the reservation form notes). */
    private const SUGGESTED_AMOUNTS = ['baptism' => 800];

    private function sb(): \Illuminate\Http\Client\PendingRequest
    {
        $key = config('services.supabase.service_role_key');

        return Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key]);
    }

    /** The signed-in customer's approved reservation with this id, or null. */
    private function ownApprovedReservation($id): ?array
    {
        if (!$id || !ctype_digit((string) $id)) {
            return null;
        }

        $response = $this->sb()->get(config('services.supabase.url') . '/rest/v1/reservations', [
            'select'      => 'id,event_type,reservation_date,reservation_time,status,name',
            'id'          => 'eq.' . $id,
            'customer_id' => 'eq.' . session('customer_id'),
            'status'      => 'eq.approved',
            'limit'       => 1,
        ]);

        return $response->successful() ? ($response->json()[0] ?? null) : null;
    }

    public function index(Request $request)
    {
        $reservation = $this->ownApprovedReservation($request->query('reservation'));

        $history = $this->sb()->get(config('services.supabase.url') . '/rest/v1/donations', [
            'select'      => 'receipt_number,amount,purpose,custom_purpose,donation_date,payment_status,gcash_reference,reservation_id',
            'customer_id' => 'eq.' . session('customer_id'),
            'order'       => 'created_at.desc',
            'limit'       => 10,
        ]);

        return view('donate.index', [
            'gcashNumber'     => config('services.gcash.number'),
            'gcashName'       => config('services.gcash.name'),
            'reservation'     => $reservation,
            'suggestedAmount' => $reservation ? (self::SUGGESTED_AMOUNTS[strtolower($reservation['event_type'] ?? '')] ?? null) : null,
            'history'         => $history->successful() ? ($history->json() ?? []) : [],
        ]);
    }

    public function store(Request $request, DonationService $donations)
    {
        $validated = $request->validate([
            'amount'          => 'required|numeric|min:1|max:1000000',
            'purpose'         => 'required|in:' . implode(',', array_keys(DonationService::PURPOSES)),
            'custom_purpose'  => 'required_if:purpose,other|nullable|string|max:255',
            'gcash_reference' => ['required', 'string', 'regex:/^[0-9 ]{6,30}$/'],
            'receipt'         => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'reservation_id'  => 'nullable|integer',
            'notes'           => 'nullable|string|max:500',
        ], [
            'gcash_reference.regex'   => 'The GCash reference number should contain digits only.',
            'receipt.required'        => 'Please upload a screenshot of your GCash receipt.',
            'custom_purpose.required_if' => 'Please say what the donation is for.',
        ]);

        if (!$donations->paymentColumnsReady()) {
            return back()->withInput()->withErrors(['donation' => 'Online giving is not available yet. Please try again later or give at the parish office.']);
        }

        $reference = preg_replace('/\s+/', '', $validated['gcash_reference']);
        if ($donations->gcashReferenceUsed($reference)) {
            return back()->withInput()->withErrors(['gcash_reference' => 'This GCash reference number was already submitted.']);
        }

        $reservation = null;
        if (!empty($validated['reservation_id'])) {
            $reservation = $this->ownApprovedReservation($validated['reservation_id']);
            if (!$reservation) {
                return back()->withInput()->withErrors(['reservation_id' => 'That reservation is not approved yet, so it cannot be paid online.']);
            }
        }

        $receiptUrl = $donations->uploadReceipt($request->file('receipt'));
        if (!$receiptUrl) {
            return back()->withInput()->withErrors(['receipt' => 'Could not upload the screenshot. Please try again.']);
        }

        $row = [
            'donor_name'      => session('customer_name', 'Online donor'),
            'amount'          => $validated['amount'],
            'purpose'         => $validated['purpose'],
            'custom_purpose'  => $validated['purpose'] === 'other' ? $validated['custom_purpose'] : null,
            'donation_date'   => now()->toDateString(),
            'notes'           => $validated['notes'] ?? null,
            'receipt_number'  => $donations->nextReceiptNumber((int) now()->format('Y')),
            'payment_method'  => 'gcash',
            'payment_status'  => 'pending',
            'gcash_reference' => $reference,
            'receipt_url'     => $receiptUrl,
            'reservation_id'  => $reservation['id'] ?? null,
            'customer_id'     => session('customer_id'),
            'source'          => 'online',
        ];

        $response = $donations->insert($row);
        if ($response->failed()) {
            return back()->withInput()->withErrors(['donation' => DonationService::insertError($response)]);
        }

        return redirect()->route($reservation ? 'reservation.my' : 'donate.index')->with('auth_notification', [
            'icon'  => 'success',
            'title' => 'Thank you!',
            'text'  => "Your GCash payment was submitted (Receipt #{$row['receipt_number']}). The parish office will verify it shortly.",
        ]);
    }
}
