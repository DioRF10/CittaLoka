<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Onboarding\TravelerOnboardingController;
use App\Http\Controllers\Onboarding\HostOnboardingController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BookingController;
use App\Models\Experience;
 use App\Http\Controllers\Host\HostDashboardController;


Route::get('/', function () {
    $featuredExperiences = Experience::with(['host.user', 'kategori', 'photos'])
        ->where('status', 'active')
        ->orderBy('is_featured', 'desc')
        ->orderBy('rating_avg', 'desc')
        ->take(4)
        ->get();

    return view('pages.home', compact('featuredExperiences'));
})->name('home');

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

Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('login')->with('status', '✓ Email berhasil terverifikasi! Silakan login dengan akun Anda.');
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
Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
Route::get('/experiences/{slug}', [ExperienceController::class, 'show'])
    ->name('experiences.show');
Route::get('/experiences/{slug}/times', [ExperienceController::class, 'getTimes'])->name('experiences.times');
Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/checkout/{slug}',         [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{slug}',        [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{kode}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/{kode}', [BookingController::class, 'show'])->name('bookings.show');
Route::patch('/bookings/{kode}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});



// =============================================================================
// Onboarding
// =============================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/onboarding/tourist', [TravelerOnboardingController::class, 'index'])
        ->name('onboarding.traveler');
    Route::post('/onboarding/tourist/save', [TravelerOnboardingController::class, 'save'])
        ->name('onboarding.traveler.save');

    Route::get('/onboarding/host', [HostOnboardingController::class, 'index'])
        ->name('onboarding.host');
    Route::post('/onboarding/host/save', [HostOnboardingController::class, 'save'])
        ->name('onboarding.host.save');
});

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('host.')->group(function () {

    // Pastikan hanya role 'host' yang bisa akses
    // Tambahkan middleware ini kalau sudah punya EnsureUserIsHost middleware:
    // Route::middleware('role:host')->group(function () { ... });
    // Atau pakai inline check di controller (sudah ada via getHost())

    // Dashboard Overview
    Route::get('/', [HostDashboardController::class, 'index'])->name('dashboard');

    // My Experiences
    Route::get('/experiences',        [HostDashboardController::class, 'experiences'])->name('experiences.index');
    Route::get('/experiences/create', [HostDashboardController::class, 'createExperience'])->name('experiences.create');

    // Bookings
    Route::get('/bookings', [HostDashboardController::class, 'bookings'])->name('bookings.index');

    // Availability
    Route::get('/availability',         [HostDashboardController::class, 'availability'])->name('availability.index');
    Route::post('/availability',        [HostDashboardController::class, 'storeAvailability'])->name('availability.store');
    Route::delete('/availability/{id}', [HostDashboardController::class, 'deleteAvailability'])->name('availability.delete');

    // Earnings
    Route::get('/earnings', [HostDashboardController::class, 'earnings'])->name('earnings');

    // Settings
    Route::get('/settings', [HostDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [HostDashboardController::class, 'updateSettings'])->name('settings.update');
});
