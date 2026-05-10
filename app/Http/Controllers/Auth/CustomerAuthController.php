<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerAuthController extends Controller
{
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $supabaseUrl = config('services.supabase.url');
    $supabaseKey = config('services.supabase.key');

    // 1. Login using Supabase Auth
    $authResponse = Http::withHeaders([
        'apikey' => $supabaseKey,
        'Content-Type' => 'application/json',
    ])->post($supabaseUrl . '/auth/v1/token?grant_type=password', [
        'email' => $request->email,
        'password' => $request->password,
    ]);

    if ($authResponse->failed()) {
        $errorCode = $authResponse->json('error_code') ?? $authResponse->json('error') ?? '';
        \Illuminate\Support\Facades\Log::warning('Supabase login failed', [
            'email'  => $request->email,
            'status' => $authResponse->status(),
            'body'   => $authResponse->body(),
        ]);

        $text = match (true) {
            str_contains($errorCode, 'email_not_confirmed') => 'Your email is not yet verified. Please check your inbox and click the confirmation link that was sent when you registered.',
            str_contains($errorCode, 'invalid_credentials') => 'Incorrect email or password. Please try again.',
            str_contains($errorCode, 'user_not_found')      => 'No account found with that email address.',
            default                                          => 'Login failed. Please check your email and password and try again.',
        };

        return back()->with([
            'auth_notification' => [
                'icon'  => 'error',
                'title' => 'Login Failed',
                'text'  => $text,
            ],
        ])->withInput();
    }

    $authUser = $authResponse->json()['user'] ?? null;
    $authId = $authUser['id'] ?? null;

    \Illuminate\Support\Facades\Log::info('Supabase auth success', [
        'email'   => $request->email,
        'auth_id' => $authId,
    ]);

    if (!$authId) {
        return back()->with([
            'auth_notification' => [
                'icon' => 'error',
                'title' => 'Login Failed',
                'text' => 'Unable to read Supabase user.',
            ],
        ])->withInput();
    }

    // 2. Get profile from customers table
    $customer = null;
    try {
        $customerResponse = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->get($supabaseUrl . '/rest/v1/customers', [
            'auth_id' => 'eq.' . $authId,
            'select' => '*',
            'limit' => 1,
        ]);

        \Illuminate\Support\Facades\Log::info('Customer lookup', [
            'auth_id' => $authId,
            'status'  => $customerResponse->status(),
            'body'    => $customerResponse->body(),
        ]);

        $customer = $customerResponse->successful() ? ($customerResponse->json()[0] ?? null) : null;
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Customer lookup exception', ['error' => $e->getMessage()]);
    }

    if (!$customer) {
        return back()->with([
            'auth_notification' => [
                'icon'  => 'error',
                'title' => 'Login Failed',
                'text'  => 'Account profile not found. Please contact the parish office or register again.',
            ],
        ])->withInput();
    }

    if (($customer['status'] ?? 'active') === 'disabled') {
        return back()->with([
            'auth_notification' => [
                'icon'  => 'error',
                'title' => 'Account Disabled',
                'text'  => 'Your account has been disabled. Please contact the parish office.',
            ],
        ])->withInput();
    }

    session([
        'customer_id'      => $customer['id'],
        'customer_auth_id' => $authId,
        'customer_name'    => $customer['name'] ?? ($authUser['user_metadata']['full_name'] ?? 'Customer'),
        'customer_email'   => $authUser['email'],
        'customer_phone'   => $customer['phone'] ?? '',
    ]);

    return redirect()->route('home')->with([
        'auth_notification' => [
            'icon'  => 'success',
            'title' => 'Login Successful',
            'text'  => 'Welcome back!',
        ],
    ]);
}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email',
    'phone' => 'nullable|string|max:20',
    // 'otp_code' => 'required|string|size:6', // SMS_DISABLED
    'password' => 'required|min:8|confirmed',
    'address' => 'required|string|max:500',
]);

// SMS_DISABLED — OTP verification skipped; re-enable when SMS is restored
// $otpResponse = Http::withHeaders([
//     'apikey'        => config('services.supabase.key'),
//     'Authorization' => 'Bearer ' . config('services.supabase.key'),
// ])->get(config('services.supabase.url') . '/rest/v1/customer_otps', [
//     'phone'       => 'eq.' . $request->phone,
//     'otp_code'    => 'eq.' . $request->otp_code,
//     'purpose'     => 'eq.registration',
//     'verified_at' => 'is.null',
//     'expires_at'  => 'gt.' . now()->toISOString(),
//     'select'      => 'id',
//     'order'       => 'created_at.desc',
//     'limit'       => 1,
// ]);
// $validOtp = $otpResponse->successful() ? ($otpResponse->json()[0] ?? null) : null;
// if (!$validOtp) {
//     return back()->withErrors([
//         'otp_code' => 'Invalid or expired OTP code.',
//     ])->withInput();
// }
// Http::withHeaders([
//     'apikey'        => config('services.supabase.key'),
//     'Authorization' => 'Bearer ' . config('services.supabase.key'),
//     'Content-Type'  => 'application/json',
// ])->patch(config('services.supabase.url') . '/rest/v1/customer_otps?id=eq.' . $validOtp['id'], [
//     'verified_at' => now()->toISOString(),
// ]);

    $supabaseUrl = config('services.supabase.url');
    $supabaseKey = config('services.supabase.key');

    // 1. Create user in Supabase Auth
    $authResponse = Http::withHeaders([
        'apikey' => $supabaseKey,
        'Content-Type' => 'application/json',
    ])->post($supabaseUrl . '/auth/v1/signup', [
        'email' => $request->email,
        'password' => $request->password,
        'data' => [
            'full_name' => $request->name,
            'address' => $request->address,
        ],
    ]);

    if ($authResponse->failed()) {
        return back()->withErrors([
            'register' => 'Supabase Auth failed: ' . $authResponse->body(),
        ])->withInput();
    }

    $authUser = $authResponse->json()['user'] ?? null;
    $authId = $authUser['id'] ?? null;

    if (!$authId) {
        return back()->withErrors([
            'register' => 'Account was created but no auth ID was returned.',
        ])->withInput();
    }

    // 2. Save profile in customers table
    $customerResponse = Http::withHeaders([
        'apikey' => $supabaseKey,
        'Authorization' => 'Bearer ' . $supabaseKey,
        'Content-Type' => 'application/json',
        'Prefer' => 'return=representation',
  ])->post($supabaseUrl . '/rest/v1/customers', [
    'auth_id' => $authId,
    'name' => $request->name,
    'email' => $request->email,
    'phone' => $request->phone,
    'address' => $request->address,
    'role' => 'customer',
]);

    if ($customerResponse->failed()) {
        return back()->withErrors([
            'register' => 'Customer profile failed: ' . $customerResponse->body(),
        ])->withInput();
    }

    return redirect()->route('login')->with([
        'auth_notification' => [
            'icon' => 'success',
            'title' => 'Registration Successful',
            'text' => 'Your account has been created. You can now log in.',
        ],
    ]);
 }

 public function showLogin()
{
    return view('auth.login'); // or your actual login blade
}

public function logout(Request $request)
{
    $request->session()->forget([
        'customer_id',
        'customer_auth_id',
        'customer_name',
        'customer_email',
        'customer_phone',
    ]);

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home')->with([
        'auth_notification' => [
            'icon' => 'success',
            'title' => 'Logged Out',
            'text' => 'You have been logged out successfully.',
        ],
    ]);
  }

  public function sendRegisterOtp(Request $request)
{
    $request->validate([
        'phone' => 'required|string|max:20',
    ]);

    $otp = (string) random_int(100000, 999999);

    Http::withHeaders([
        'apikey'        => config('services.supabase.key'),
        'Authorization' => 'Bearer ' . config('services.supabase.key'),
        'Content-Type'  => 'application/json',
    ])->post(config('services.supabase.url') . '/rest/v1/customer_otps', [
        'phone'      => $request->phone,
        'otp_code'   => $otp,
        'purpose'    => 'registration',
        'expires_at' => now()->addMinutes(5)->toISOString(),
        'created_at' => now()->toISOString(),
    ]);

    // SMS_DISABLED — re-enable when SMS is restored
    // app(\App\Services\SmsNotificationService::class)->send(
    //     $request->phone,
    //     "ParishSched OTP: {$otp}. Use this code to verify your account. It expires in 5 minutes. Do not share this code."
    // );

    return back()->with([
        'auth_notification' => [
            'icon' => 'success',
            'title' => 'OTP Sent',
            'text' => 'A verification code was sent to your phone number.',
        ],
    ])->withInput();
}

}