<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/schedule', function () {
    return view('schedule.index');
})->name('schedule');

Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);

Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);

Route::get('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

Route::get('/reservation', [ReservationController::class, 'index'])
    ->middleware(['customer.auth', 'customer.status'])
    ->name('reservation.index');

Route::post('/reservation', [ReservationController::class, 'store'])
    ->middleware(['customer.auth', 'customer.status'])
    ->name('reservation.store');

Route::get('/reservation_view/{id}', [ReservationController::class, 'show'])
    ->name('reservation.view');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
    ->name('password.email');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
    ->name('password.update');

Route::get('/contact', function () {
    return view('contact.index');
})->name('contact');

Route::post('/contact/send', [ContactController::class, 'send'])
    ->name('contact.send');

Route::post('/api/mobile-contact', [ContactController::class, 'mobileSend']);

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin', [AdminController::class, 'handle'])->name('admin.handle');
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::post('/send-register-otp', [\App\Http\Controllers\Auth\CustomerAuthController::class, 'sendRegisterOtp'])
    ->name('register.sendOtp');