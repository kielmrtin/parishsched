<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    private function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $phone);

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '09') && strlen($digits) === 11) {
            return '63' . substr($digits, 1);
        }

        if (str_starts_with($digits, '9') && strlen($digits) === 10) {
            return '63' . $digits;
        }

        return $digits;
    }

    public function send(?string $phone, string $message): bool
    {
        if (!filter_var(config('services.iprog_sms.enabled'), FILTER_VALIDATE_BOOLEAN)) {
            Log::info('SMS notifications disabled via SMS_NOTIFICATIONS_ENABLED; skipping send.', [
                'phone' => $phone,
            ]);
            return false;
        }

        $phone = $this->normalizePhone($phone);

        if ($phone === '' || trim($message) === '') {
            Log::warning('SMS skipped: missing phone or message.', [
                'phone' => $phone,
                'message' => $message,
            ]);
            return false;
        }

        try {
            $response = Http::timeout(15)
                ->post(config('services.iprog_sms.url'), [
                    'api_token' => config('services.iprog_sms.token'),
                    'message' => trim($message),
                    'phone_number' => $phone,
                ]);

            Log::info('SMS response', [
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('SMS failed: ' . $e->getMessage());
            return false;
        }
    }
}