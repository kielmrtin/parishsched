<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $url = rtrim(config('services.supabase.url'), '/');
        $key = config('services.supabase.key');

        $response = Http::withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
        ])->get(config('services.supabase.url') . '/rest/v1/announcements' , [
            'select' => '*',
            'show_on_home' => 'eq.true',
            'order' => 'created_at.desc',
        ]);

        $announcements = $response->successful() ? $response->json() : [];

        return view('home', compact('announcements'));
    }
}