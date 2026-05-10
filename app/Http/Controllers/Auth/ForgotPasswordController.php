<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    private function headers(): array
    {
        return [
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
        ];
    }

    private function url(string $table, array $query = []): string
    {
        $url = rtrim(config('services.supabase.url'), '/') . '/rest/v1/' . $table;

        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower(trim($request->email));

        $customerResponse = Http::withHeaders($this->headers())
            ->get($this->url('customers'), [
                'email' => 'eq.' . $email,
                'select' => 'id,name,email',
                'limit' => 1,
            ]);

        $customer = $customerResponse->json()[0] ?? null;

        if ($customer) {
            Http::withHeaders($this->headers())
                ->delete($this->url('customer_password_resets', [
                    'customer_id' => 'eq.' . $customer['id'],
                ]));

            $token = Str::random(64);
            $tokenHash = hash('sha256', $token);

            $insertResponse = Http::withHeaders($this->headers())
                ->post($this->url('customer_password_resets'), [
                    'customer_id' => $customer['id'],
                    'token_hash' => $tokenHash,
                    'expires_at' => Carbon::now('UTC')->addHour()->toISOString(),
                ]);

            if ($insertResponse->failed()) {
                return back()->with('error', 'Reset token failed to save: ' . $insertResponse->body());
            }

            $resetUrl = route('password.reset', ['token' => $token]);

            Mail::raw(
                "Hi " . ($customer['name'] ?? 'there') . ",\n\n" .
                "Click this link to reset your password:\n\n" .
                $resetUrl . "\n\n" .
                "This link will expire in one hour.\n\n" .
                "St. John the Baptist Parish",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Reset your St. John the Baptist Parish password');
                }
            );
        }

        return back()->with('success', "If an account matches that email address, we've sent a password reset link.");
    }

    public function showResetForm(Request $request)
    {
        $token = trim((string) $request->query('token', ''));

        if ($token === '' || !$this->getValidResetRequest($token)) {
            return view('auth.reset-password', [
                'token' => '',
                'canShowForm' => false,
            ])->with('error', 'The password reset link is invalid or has expired. Please request a new one.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'canShowForm' => true,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        $resetRequest = $this->getValidResetRequest($request->token);

        if (!$resetRequest) {
            return view('auth.reset-password', [
                'token' => '',
                'canShowForm' => false,
            ])->with('error', 'The password reset link is invalid or has expired. Please request a new one.');
        }

        $customerId = $resetRequest['customer_id'];

        // Get the customer's Supabase Auth ID
        $customerResponse = Http::withHeaders($this->headers())
            ->get($this->url('customers'), [
                'id'     => 'eq.' . $customerId,
                'select' => 'id,auth_id',
                'limit'  => 1,
            ]);

        $customer = $customerResponse->json()[0] ?? null;

        if (!$customer || empty($customer['auth_id'])) {
            return back()->with('error', 'Customer account not found. Please contact the parish office.');
        }

        // Update the password in Supabase Auth via the Admin API (requires service role key)
        $adminResponse = Http::withHeaders([
            'apikey'        => config('services.supabase.service_role_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_role_key'),
            'Content-Type'  => 'application/json',
        ])->put(
            rtrim(config('services.supabase.url'), '/') . '/auth/v1/admin/users/' . $customer['auth_id'],
            ['password' => $request->password]
        );

        if ($adminResponse->failed()) {
            return back()->with('error', 'Password update failed. Please try again or contact the parish office.');
        }

        // Delete the used reset token
        Http::withHeaders($this->headers())
            ->delete($this->url('customer_password_resets', [
                'customer_id' => 'eq.' . $customerId,
            ]));

        // Clear the password_reset_required flag
        Http::withHeaders($this->headers())
            ->patch($this->url('customers', ['id' => 'eq.' . $customerId]), [
                'password_reset_required' => false,
            ]);

        return redirect()->route('login')->with([
            'auth_notification' => [
                'icon'  => 'success',
                'title' => 'Password Updated',
                'text'  => 'Your password has been updated. You can now log in.',
            ],
        ]);
    }

    private function getValidResetRequest(string $token): ?array
    {
        $tokenHash = hash('sha256', $token);

        $response = Http::withHeaders($this->headers())
            ->get($this->url('customer_password_resets'), [
                'token_hash' => 'eq.' . $tokenHash,
                'expires_at' => 'gt.' . Carbon::now('UTC')->toISOString(),
                'select' => '*',
                'limit' => 1,
            ]);

        return $response->json()[0] ?? null;
    }
}