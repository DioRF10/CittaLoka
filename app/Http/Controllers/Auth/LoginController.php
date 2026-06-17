<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function index()
    {
        return view('auth.login');
    }

    // Proses login
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Cek rate limiting (max 5x salah = kunci 15 menit)
        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // 3. Coba login
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 60 * 15); // Kunci 15 menit
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput($request->only('email'));
        }

        // 4. Login berhasil — reset rate limiter
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $user = Auth::user();

        // 6. Redirect berdasarkan role
        return redirect()->intended($this->redirectAfterLogin($user));
    }

    // Logout
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Tentukan redirect setelah login berdasarkan role
    private function redirectAfterLogin($user): string
{
    // Belum onboarding → ke onboarding dulu
    if (!$user->onboarding_completed_at) {
        return match($user->role) {
            'host'  => route('onboarding.host'),
            default => route('onboarding.traveler'),
        };
    }

    // Sudah onboarding → ke halaman utama sesuai role
    return match($user->role) {
        'host'  => route('host.dashboard'),
        'admin' => '/admin',
        default => route('experiences.index'),
    };
}
}