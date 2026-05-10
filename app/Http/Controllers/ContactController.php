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
        Mail::raw(
            "New Inquiry\n\n" .
            "Name: {$validated['name']}\n" .
            "Email: {$validated['email']}\n" .
            "Phone: " . ($validated['phone'] ?? 'N/A') . "\n\n" .
            "Message:\n{$validated['message']}",
            function ($message) use ($validated) {
                $message->to('kiyelmartinpogi@gmail.com')
                    ->subject('New inquiry from ' . $validated['name'])
                    ->replyTo($validated['email'], $validated['name']);
            }
        );

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
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    Mail::raw(
        "Name: {$validated['name']}\n" .
        "Email: {$validated['email']}\n" .
        "Subject: {$validated['subject']}\n\n" .
        "Message:\n{$validated['message']}",
        function ($mail) use ($validated) {
            $mail->to('kiyelmartinpogi@gmail.com')
                ->subject('Mobile Inquiry: ' . $validated['subject'])
                ->replyTo($validated['email']);
        }
    );

    return response()->json([
        'success' => true,
        'message' => 'Inquiry sent successfully.',
    ]);
 }
}