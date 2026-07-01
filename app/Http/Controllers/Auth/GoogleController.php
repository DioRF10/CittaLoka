<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirect ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback setelah login Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['email' => 'Login Google gagal: ' . $e->getMessage()]);
        }

        // Cek apakah email sudah terdaftar
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // User sudah ada — update google_id kalau belum ada
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            // User baru — ambil role dari session atau default 'user'
            $role = session('register_role', 'user');

            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null,
                'role' => $role,
                'email_verified_at' => now(), // Google sudah verifikasi email
            ]);

            session()->forget('register_role');
        }

        // Login user
        Auth::login($user, remember: true);

        // Redirect berdasarkan onboarding status dan role
        return redirect()->intended($this->redirectAfterLogin($user));
    }

    // Tentukan redirect setelah login berdasarkan role dan onboarding
    private function redirectAfterLogin($user): string
    {
        // Belum onboarding → ke onboarding dulu
        if (!$user->onboarding_completed_at) {
            return match ($user->role) {
                'host' => route('onboarding.host'),
                default => route('onboarding.traveler'),
            };
        }

        // Sudah onboarding → ke halaman utama sesuai role
        return match ($user->role) {
            'host' => route('host.dashboard'),
            'admin' => '/admin',
            default => route('experiences.index'),
        };
    }
}