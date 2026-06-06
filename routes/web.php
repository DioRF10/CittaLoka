<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// =============================================================================
// AUTH — Register
// =============================================================================
Route::get('/register', function () {
    return view('auth.choose-role');
})->name('register');

Route::post('/register/set-role', function (Request $request) {
    $role = $request->input('role');
    if (!in_array($role, ['host', 'user'])) {
        return back();
    }
    session(['register_role' => $role]);
    return redirect()->route('register.form');
})->name('register.set-role');

Route::get('/register/form', function () {
    return view('auth.register');
})->name('register.form');

Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// =============================================================================
// AUTH — Login
// =============================================================================
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// =============================================================================
// AUTH — Email Verification
// =============================================================================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (
    Illuminate\Foundation\Auth\EmailVerificationRequest $request
) {
    $request->fulfill();
    $user = auth()->user();
    return redirect()->intended(
        $user->role === 'host' ? '/dashboard' : '/experiences'
    );
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// =============================================================================
// AUTH — Forgot Password
// =============================================================================
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordController::class, 'request'])
    ->middleware('guest')->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordController::class, 'email'])
    ->middleware('guest')->name('password.email');

Route::get('/forgot-password/check-inbox', [App\Http\Controllers\Auth\PasswordController::class, 'checkInbox'])
    ->middleware('guest')->name('password.check-inbox');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\PasswordController::class, 'reset'])
    ->middleware('guest')->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\PasswordController::class, 'store'])
    ->middleware('guest')->name('password.store');

// =============================================================================
// Google OAuth
// =============================================================================
Route::get('/auth/google/redirect', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])->name('auth.google.callback');

// =============================================================================
// Protected pages (sementara)
// =============================================================================
Route::get('/experiences', function () {
    return '<h1>Experiences — coming soon</h1>';
})->name('experiences.index');

Route::get('/dashboard', function () {
    return '<h1>Dashboard Host — coming soon</h1>';
})->name('dashboard.index');