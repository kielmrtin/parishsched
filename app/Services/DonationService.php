<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

/**
 * Shared donation rules for the admin donation records and the customer
 * "Give online" page (GCash / cheque panel revision).
 */
class DonationService
{
    public const PURPOSES = [
        'building_fund'  => 'Building Fund',
        'mass_intention' => 'Mass Intention',
        'tithes'         => 'Tithes',
        'other'          => 'Other',
    ];

    public const METHODS = ['cash' => 'Cash', 'gcash' => 'GCash', 'cheque' => 'Cheque'];

    public const STATUSES = [
        'received'  => 'Received',
        'pending'   => 'Pending verification',
        'verified'  => 'Verified',
        'rejected'  => 'Rejected',
        'uncleared' => 'Uncleared',
        'cleared'   => 'Cleared',
    ];

    /** Statuses whose money has actually reached the parish. */
    public const COUNTED_STATUSES = ['received', 'verified', 'cleared'];

    private function sb(): \Illuminate\Http\Client\PendingRequest
    {
        $key = config('services.supabase.service_role_key');

        return Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key]);
    }

    private function url(string $path): string
    {
        return rtrim(config('services.supabase.url'), '/') . $path;
    }

    /** Donations saved before payment methods existed have no status: treat them as received. */
    public static function isCounted(array $donation): bool
    {
        return in_array($donation['payment_status'] ?? 'received', self::COUNTED_STATUSES, true);
    }

    /** Next RCP-{YEAR}-{0001} number, one past the highest used that year (safe after deletions). */
    public function nextReceiptNumber(int $year): string
    {
        $response = $this->sb()->get($this->url('/rest/v1/donations'), [
            'select'         => 'receipt_number',
            'receipt_number' => 'like.RCP-' . $year . '-*',
        ]);

        $max = 0;
        foreach ($response->successful() ? ($response->json() ?? []) : [] as $row) {
            if (preg_match('/^RCP-\d{4}-(\d+)$/', (string) ($row['receipt_number'] ?? ''), $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return 'RCP-' . $year . '-' . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
    }

    /** False until supabase/donation_payments.sql has added the payment columns. */
    public function paymentColumnsReady(): bool
    {
        return $this->sb()->get($this->url('/rest/v1/donations'), [
            'select' => 'payment_status,gcash_reference,receipt_url',
            'limit'  => 1,
        ])->successful();
    }

    /** True when this GCash reference was already submitted and not rejected. */
    public function gcashReferenceUsed(string $reference): bool
    {
        $response = $this->sb()->get($this->url('/rest/v1/donations'), [
            'select'          => 'id',
            'gcash_reference' => 'eq.' . $reference,
            'payment_status'  => 'neq.rejected',
            'limit'           => 1,
        ]);

        return $response->successful() && !empty($response->json());
    }

    /** Uploads a GCash receipt screenshot; returns its public URL or null. */
    public function uploadReceipt(UploadedFile $file): ?string
    {
        $bucket = 'reservation-attachments';
        $path   = 'gcash-receipts/' . now()->format('Y/m') . '/' . time() . '_' . bin2hex(random_bytes(6)) . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');

        $response = $this->sb()
            ->withHeaders(['Content-Type' => $file->getMimeType()])
            ->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
            ->post($this->url("/storage/v1/object/{$bucket}/{$path}"));

        return $response->successful() ? $this->url("/storage/v1/object/public/{$bucket}/{$path}") : null;
    }

    /** Inserts a donation row; returns the Supabase response. */
    public function insert(array $row): \Illuminate\Http\Client\Response
    {
        return $this->sb()
            ->withHeaders(['Content-Type' => 'application/json', 'Prefer' => 'return=representation'])
            ->post($this->url('/rest/v1/donations'), $row);
    }

    /** A readable message for a failed insert, pointing at the migration when columns are missing. */
    public static function insertError(\Illuminate\Http\Client\Response $response): string
    {
        $message = (string) ($response->json('message') ?? $response->body());
        if (str_contains($message, 'column') && str_contains($message, 'donations')) {
            return 'The donations table is missing the payment columns. Run supabase/donation_payments.sql in the Supabase SQL Editor first.';
        }
        if (str_contains($message, 'donations_gcash_reference_key')) {
            return 'This GCash reference number was already submitted.';
        }

        return 'Could not save the donation: ' . $message;
    }
}
