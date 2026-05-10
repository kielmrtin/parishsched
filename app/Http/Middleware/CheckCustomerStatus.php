<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckCustomerStatus
{
    public function handle(Request $request, Closure $next)
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return $next($request);
        }

        try {
            $response = Http::withHeaders([
                'apikey'        => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
            ])->get(config('services.supabase.url') . '/rest/v1/customers', [
                'id'     => 'eq.' . $customerId,
                'select' => 'id,status',
                'limit'  => 1,
            ]);

            $customerData = $response->successful() ? ($response->json()[0] ?? null) : null;
        } catch (\Throwable) {
            $customerData = null;
        }

        if (!$customerData || ($customerData['status'] ?? 'active') === 'disabled') {
            $request->session()->forget([
                'customer_id',
                'customer_auth_id',
                'customer_name',
                'customer_email',
                'customer_phone',
            ]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with([
                'auth_notification' => [
                    'icon' => 'error',
                    'title' => 'Account Disabled',
                    'text' => 'Your account has been disabled by the administrator.',
                ],
            ]);
        }

        return $next($request);
    }
}