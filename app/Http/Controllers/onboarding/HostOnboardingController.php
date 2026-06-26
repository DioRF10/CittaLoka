<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\Host;
use App\Services\XenditService;
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
            return redirect()->route('host.dashboard');
        }

        Host::firstOrCreate(['user_id' => $user->id]);

        return view('onboarding.host');
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        $host = Host::where('user_id', $user->id)->firstOrFail();
        $step = $request->input('step');

        // Step 2 — Bahasa & Lokasi
        if ($step == 2) {
            $request->validate([
                'locale' => ['required', 'in:id,en,mix'],
                'village' => ['required', 'string', 'max:100'],
            ]);

            // 'locale' untuk user tetap pakai id/en standar; 'mix' disimpan terpisah di host
            $user->fill(['locale' => $request->input('locale') === 'en' ? 'en' : 'id'])->save();

            $host->village = $request->input('village');
            $host->language_preference = $request->input('locale');
            $host->save();
        }

        // Step 3 — Profil Host (Story)
        if ($step == 3) {
            $request->validate([
                'full_name' => ['required', 'string', 'max:100'],
                'phone_number' => ['required', 'string', 'max:20'],
                'age' => ['nullable', 'integer', 'min:17', 'max:100'],
                'bio' => ['nullable', 'string', 'max:300'],
                'story' => ['nullable', 'string', 'max:500'],
                'expertise' => ['required', 'string'], // JSON string dari frontend
                'avatar' => ['nullable', 'image', 'max:2048'],
            ]);

            $expertise = json_decode($request->input('expertise'), true);
            if (!is_array($expertise) || count($expertise) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal satu keahlian utama.',
                ], 422);
            }

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars/hosts', 'public');
                $user->fill(['avatar' => $path])->save();
            }

            // Update nama user juga (pre-filled dari register, bisa diedit di sini)
            $user->fill(['name' => $request->input('full_name')])->save();

            $host->phone_number = $request->input('phone_number');
            $host->age = $request->input('age');
            $host->bio = $request->input('bio');
            $host->story = $request->input('story');
            $host->expertise = $expertise;
            $host->save();
        }

        // Step 4 — Upload KTP + Selfie dengan KTP
        if ($step == 4) {
            $request->validate([
                'ktp_photo' => ['required', 'image', 'max:5120'],
                'ktp_selfie' => ['required', 'image', 'max:5120'],
            ]);

            if ($host->ktp_path) {
                Storage::disk('public')->delete($host->ktp_path);
            }
            if ($host->ktp_selfie_path ?? null) {
                Storage::disk('public')->delete($host->ktp_selfie_path);
            }

            $host->ktp_path = $request->file('ktp_photo')->store('ktp', 'public');
            $host->ktp_selfie_path = $request->file('ktp_selfie')->store('ktp', 'public');
            $host->ktp_status = 'pending';
            $host->save();
        }

        // Step 5 — Data bank + verifikasi Xendit + complete onboarding
        if ($step == 5) {
            $request->validate([
                'bank_name' => ['required', 'string', 'max:100'],
                'bank_account_name' => ['required', 'string', 'max:100'],
                'bank_account_number' => ['required', 'string', 'max:50'],
                'confirm_bank' => ['required', 'accepted'],
            ]);

            // ── Verifikasi Rekening Manual ──
            // Sistem diubah menjadi verifikasi manual oleh admin (Xendit Inquiry di-bypass)

            $host->bank_name = $request->input('bank_name');
            $host->bank_account_name = $request->input('bank_account_name');
            $host->bank_account_number = $request->input('bank_account_number');
            
            $host->bank_review_status = 'needs_review';
            $host->bank_verified_at = null;
            $host->bank_review_note = 'Menunggu verifikasi manual oleh admin.';

            $host->save();

            $user->fill(['onboarding_completed_at' => now()->toDateTimeString()])->save();

            return response()->json([
                'success' => true,
                'redirect' => route('host.dashboard'),
                'bank_status' => $host->bank_review_status,
                'bank_message' => 'Rekening Anda telah disimpan dan sedang dalam proses verifikasi manual oleh admin.',
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mapping nama bank dari dropdown (format manusiawi) ke kode bank Xendit.
     */
    private function mapBankNameToXenditCode(string $bankName): string
    {
        $map = [
            'BCA' => 'BCA',
            'BRI' => 'BRI',
            'BNI' => 'BNI',
            'Mandiri' => 'MANDIRI',
            'BSI' => 'BSI',
            'CIMB Niaga' => 'CIMB',
            'Danamon' => 'DANAMON',
            'Permata' => 'PERMATA',
        ];

        return $map[$bankName] ?? strtoupper($bankName);
    }
}