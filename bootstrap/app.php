<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'customer.auth' => \App\Http\Middleware\CustomerAuthenticated::class,
        'customer.status' => \App\Http\Middleware\CheckCustomerStatus::class,
    ]);

    // Supabase's webhook call has no Laravel session/CSRF token — it's
    // authenticated by the X-Webhook-Secret header instead (see
    // ReservationWebhookController). The mobile app's contact form is the
    // same story: no Laravel session to carry a CSRF token at all.
    $middleware->validateCsrfTokens(except: [
        'webhooks/supabase/reservation-submitted',
        'api/mobile-contact',
    ]);
})

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
