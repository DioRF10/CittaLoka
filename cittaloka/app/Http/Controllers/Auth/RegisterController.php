<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'terms.accepted' => 'Kamu harus menyetujui syarat & ketentuan.',
        ]);

        // 2. Ambil role dari session (host atau user)
        $role = session('register_role', 'user');
        if (!in_array($role, ['host', 'user'])) {
            $role = 'user';
        }

        // 3. Simpan user ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        // 4. Kirim email verifikasi
        event(new Registered($user));

        // 5. Login otomatis setelah register
        Auth::login($user);

        // 6. Hapus role dari session
        session()->forget('register_role');

        // 7. Redirect ke halaman verifikasi email
        return redirect()->route('verification.notice');
    }
}