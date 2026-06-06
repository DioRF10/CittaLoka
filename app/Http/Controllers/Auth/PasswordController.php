<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    // Tampilkan form forgot password
    public function request()
    {
        return view('auth.forgot-password');
    }

    // Kirim link reset password ke email
    public function email(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            // Simpan email ke session untuk ditampilkan di halaman check-inbox
            session(['reset_email' => $request->email]);
            return redirect()->route('password.check-inbox');
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }

    // Tampilkan halaman "Check your inbox"
    public function checkInbox()
    {
        return view('auth.check-inbox');
    }

    // Tampilkan form reset password
    public function reset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Proses reset password
    public function store(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', 'Password berhasil direset! Silakan login.');
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }
}