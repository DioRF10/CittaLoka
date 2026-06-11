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
            return redirect()->route('login')
                ->withErrors(['email' => 'Login Google gagal. Coba lagi.']);
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
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'password'          => null,
                'role'              => $role,
                'email_verified_at' => now(), // Google sudah verifikasi email
            ]);

            session()->forget('register_role');
        }

        // Login user
        Auth::login($user, remember: true);

        // Redirect langsung ke homepage
        return redirect('/');
    }
}