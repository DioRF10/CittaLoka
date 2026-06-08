<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\Host;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HostOnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'host') {
            return redirect()->route('onboarding.traveler');
        }

        if ($user->onboarding_completed_at) {
            return redirect()->route('dashboard.index');
        }

        Host::firstOrCreate(['user_id' => $user->id]);

        return view('onboarding.host');
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        $host = Host::where('user_id', $user->id)->firstOrFail();
        $step = $request->input('step');

        // Step 2 — Pilih bahasa
        if ($step == 2) {
            $request->validate([
                'locale' => ['required', 'in:id,en'],
            ]);
            $user->fill(['locale' => $request->input('locale')])->save();
        }

        // Step 3 — Profil (bio + village + foto)
        if ($step == 3) {
            $request->validate([
                'bio'     => ['nullable', 'string', 'max:1000'],
                'village' => ['nullable', 'string', 'max:100'],
                'avatar'  => ['nullable', 'image', 'max:2048'],
            ]);

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars/hosts', 'public');
                $user->fill(['avatar' => $path])->save();
            }

            $host->bio     = $request->input('bio');
            $host->village = $request->input('village');
            $host->save();
        }

        // Step 4 — Upload KTP
        if ($step == 4) {
            $request->validate([
                'ktp_photo' => ['required', 'image', 'max:5120'],
            ]);

            if ($host->ktp_path) {
                Storage::disk('public')->delete($host->ktp_path);
            }

            $ktpPath          = $request->file('ktp_photo')->store('ktp', 'public');
            $host->ktp_path   = $ktpPath;
            $host->ktp_status = 'pending';
            $host->save();
        }

        // Step 5 — Data bank + complete onboarding
        if ($step == 5) {
            $request->validate([
                'bank_name'           => ['required', 'string', 'max:100'],
                'bank_account_name'   => ['required', 'string', 'max:100'],
                'bank_account_number' => ['required', 'string', 'max:50'],
                'confirm_bank'        => ['required', 'accepted'],
            ]);

            $host->bank_name           = $request->input('bank_name');
            $host->bank_account_name   = $request->input('bank_account_name');
            $host->bank_account_number = $request->input('bank_account_number');
            $host->save();

            $user->fill(['onboarding_completed_at' => now()->toDateTimeString()])->save();

            return response()->json([
                'success'  => true,
                'redirect' => route('dashboard.index'),
            ]);
        }

        return response()->json(['success' => true]);
    }
}