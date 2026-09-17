<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:50',
        'message' => 'required|string|min:5',
    ]);

    try {
        Mail::send('emails.contact-message', [
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'bodyMessage' => $validated['message'],
        ], function ($message) use ($validated) {
            $message->to('kiyelmartinpogi@gmail.com')
                ->subject('New inquiry from ' . $validated['name'])
                ->replyTo($validated['email'], $validated['name']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully.',
        ]);
    } catch (\Throwable $e) {
        Log::error('CONTACT ERROR: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'We could not send your message right now. Please try again later.',
        ], 500);
    }
 }


public function mobileSend(Request $request)
{
    // Same fields and email template as send() above — this just gives the
    // Flutter app (which has no session/CSRF token) its own endpoint.
    $validated = $request->validate([
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:50',
        'message' => 'required|string|min:5',
    ]);

    try {
        Mail::send('emails.contact-message', [
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'bodyMessage' => $validated['message'],
        ], function ($message) use ($validated) {
            $message->to(config('services.admin.notification_email'))
                ->subject('New inquiry from ' . $validated['name'])
                ->replyTo($validated['email'], $validated['name']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully.',
        ]);
    } catch (\Throwable $e) {
        Log::error('Mobile contact email failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'We could not send your message right now. Please try again later.',
        ], 500);
    }
 }
}