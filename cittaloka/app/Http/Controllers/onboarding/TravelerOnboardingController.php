<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TravelerOnboardingController extends Controller
{
    // Tampilkan halaman onboarding traveler
    public function index()
    {
        $user = Auth::user();

        // Sudah onboarding → ke experiences
        if ($user->onboarding_completed_at) {
            return redirect()->route('experiences.index');
        }

        return view('onboarding.traveler');
    }

    // Simpan data per step via POST
    public function save(Request $request)
    {
        $user = Auth::user();
        $step = $request->input('step');

        // Step 2 — Pilih bahasa
        if ($step == 2) {
            $request->validate([
                'locale' => ['required', 'in:id,en'],
            ]);
            $user->fill(['locale' => $request->input('locale')])->save();
        }

        // Step 3 — Upload foto profil (opsional)
        if ($step == 3) {
            if ($request->hasFile('avatar')) {
                $request->validate([
                    'avatar' => ['image', 'max:2048'],
                ]);
                // Hapus avatar lama jika ada
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->fill(['avatar' => $path])->save();
            }
        }

        // Complete — tandai onboarding selesai
        if ($step === 'complete') {
            $user->fill(['onboarding_completed_at' => now()])->save();

            return response()->json([
                'success'  => true,
                'redirect' => route('experiences.index'),
            ]);
        }

        return response()->json(['success' => true]);
    }
}