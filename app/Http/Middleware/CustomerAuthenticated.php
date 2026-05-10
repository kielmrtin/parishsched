<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('customer_id')) {
            return redirect()->route('login')->with([
                'auth_notification' => [
                    'icon' => 'warning',
                    'title' => 'Login Required',
                    'text' => 'Please log in first.',
                ],
            ]);
        }

        return $next($request);
    }
}