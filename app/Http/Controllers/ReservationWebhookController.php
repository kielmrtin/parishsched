<?php

namespace App\Http\Controllers;

use App\Mail\ReservationCustomerConfirmation;
use App\Mail\ReservationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

/**
 * Receives Supabase's Database Webhook for INSERTs on `reservations`.
 *
 * Both the website (ReservationController::store) and the Flutter app write
 * directly to this table, but only the website goes through Laravel code —
 * the app talks to Supabase directly and has no way to trigger Mail::send.
 * A single DB-level webhook means "a reservation was submitted" always
 * routes through here regardless of which client created it, so the same
 * two emails (admin notification + customer confirmation) always fire
 * exactly once no matter where the reservation came from.
 */
class ReservationWebhookController extends Controller
{
    public function notify(Request $request)
    {
        $secret = config('services.supabase.webhook_secret');
        if (!$secret || $request->header('X-Webhook-Secret') !== $secret) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $payload = $request->json()->all();

        if (($payload['type'] ?? null) !== 'INSERT' || ($payload['table'] ?? null) !== 'reservations') {
            return response()->json(['message' => 'Ignored']);
        }

        $record = $payload['record'] ?? null;
        if (!$record || empty($record['email'])) {
            return response()->json(['message' => 'Missing record data'], Response::HTTP_BAD_REQUEST);
        }

        $mailData = [
            'name' => $record['name'] ?? '',
            'email' => $record['email'],
            'phone' => $record['phone'] ?? '',
            'event_type' => $record['event_type'] ?? '',
            'reservation_date' => $record['reservation_date'] ?? '',
            'reservation_time' => $record['reservation_time'] ?? '',
            'notes' => $record['notes'] ?? null,
        ];

        // The sacrament-specific fields (child/couple/deceased names, seminar
        // date, etc.) live in the `details` JSON column, not as top-level
        // columns — merge in whichever block matches this reservation's type
        // so the emails can show the same information the customer entered.
        $details = $record['details'] ?? [];
        if (is_string($details)) {
            $details = json_decode($details, true) ?? [];
        }
        $eventType = strtolower($mailData['event_type']);
        if (in_array($eventType, ['baptism', 'wedding', 'funeral'], true) && !empty($details[$eventType])) {
            $mailData[$eventType] = $details[$eventType];
        }

        $files = $this->fetchAttachments($record['id'] ?? null);

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
        } catch (\Throwable $e) {
            Log::error('Customer reservation confirmation email failed: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Notified']);
    }

    /**
     * Attachments are inserted in a separate follow-up request after the
     * reservation row itself (both from the website and the app), so they
     * may not exist yet the instant this webhook fires. Best-effort only —
     * an email without an attachment list isn't wrong, just less complete.
     */
    private function fetchAttachments(?string $reservationId): array
    {
        if (!$reservationId) {
            return [];
        }

        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
        ])->get(config('services.supabase.url') . '/rest/v1/reservation_attachments', [
            'select' => 'field_key,label,file_url',
            'reservation_id' => 'eq.' . $reservationId,
        ]);

        return $response->successful() ? ($response->json() ?? []) : [];
    }
}
