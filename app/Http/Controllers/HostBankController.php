<?php

namespace App\Http\Controllers;

use App\Models\Host;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostBankController extends Controller
{
    /**
     * Submit & verifikasi rekening bank host.
     * Dipakai dari: Onboarding Step 5 DAN Settings > Account & Bank.
     *
     * Route: POST /host/bank-account/verify
     */
    public function verify(Request $request)
    {
        $request->validate([
            'bank_name'           => 'required|string',     // contoh: BCA, MANDIRI, BNI, BRI
            'bank_account_number' => 'required|string|min:8|max:20',
            'bank_account_name'   => 'required|string|max:255',
        ]);

        $host = Auth::user()->host;
        if (!$host) {
            return back()->with('error', 'Akun host tidak ditemukan.');
        }

        // ── Cek apakah ini ganti rekening (sudah pernah verified sebelumnya) ──
        $isChangingAccount = $host->bank_review_status === 'verified'
            && $host->bank_account_number !== $request->bank_account_number;

        // ── Ambil nama KTP host untuk dicocokkan ──
        // Asumsi: nama KTP tersimpan di kolom users.name atau host punya kolom sendiri.
        // Sesuaikan ini dengan struktur project (misal $host->user->name).
        $ktpName = $host->user->name ?? $request->bank_account_name;

        $xendit = app(XenditService::class);
        $result = $xendit->verifyHostBankAccount(
            bankCode: $request->bank_name,
            accountNumber: $request->bank_account_number,
            ktpName: $ktpName
        );

        if (!$result['success']) {
            return back()->with('error', $result['error_message']);
        }

        // ── Tentukan status berdasarkan hasil pencocokan ──
        $reviewStatus = $result['is_match'] ? 'verified' : 'needs_review';

        $host->update([
            'bank_name'           => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name'   => $request->bank_account_name,
            'bank_account_holder' => $result['account_name'], // nama asli dari Xendit
            'bank_account_last4'  => substr($request->bank_account_number, -4),
            'bank_review_status'  => $reviewStatus,
            'bank_verified_at'    => $result['is_match'] ? now() : null,
            'bank_review_note'    => $result['is_match']
                ? null
                : "Nama rekening ('{$result['account_name']}') tidak cocok dengan nama akun ('{$ktpName}'). Menunggu review admin.",
            'bank_reviewed_by'    => null,
            'bank_reviewed_at'    => null,
        ]);

        if ($result['is_match']) {
            return back()->with('success', 'Rekening berhasil diverifikasi! ✅');
        }

        return back()->with('warning',
            "Rekening tersimpan, tapi nama pemilik ('{$result['account_name']}') tidak sama persis dengan nama akun kamu. " .
            "Tim kami akan meninjau secara manual sebelum pencairan dana pertama. Tidak perlu mengulang langkah ini."
        );
    }
}