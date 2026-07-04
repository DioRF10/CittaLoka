<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ── Tampilkan halaman My Profile ────────────────────────────────────────
    public function index()
    {
        $user = Auth::user();

        return view('pages.profile', compact('user'));
    }

    // ── Update profile — dispatch berdasarkan tab ───────────────────────────
    public function update(Request $request)
    {
        $tab = $request->input('tab', 'public');

        return match ($tab) {
            'contact' => $this->updateContact($request),
            'security' => $this->updateSecurity($request),
            default => $this->updatePublic($request),
        };
    }

    // ── Tab 1: Profil Publik (foto, nama, bahasa) ───────────────────────────
    private function updatePublic(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'locale' => ['required', 'in:id,en'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name = $request->input('name');
        $user->locale = $request->input('locale');
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ── Tab 2: Kontak (no. HP + kode negara) ────────────────────────────────
    private function updateContact(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'country_code' => ['nullable', 'string', 'max:5'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user->country_code = $request->input('country_code');
        $user->phone_number = $request->input('phone_number');
        $user->save();

        return back()->with('success', 'Kontak berhasil diperbarui.');
    }

    // ── Tab 3: Keamanan (ganti password) ────────────────────────────────────
    private function updateSecurity(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Password berhasil diganti.');
    }
}