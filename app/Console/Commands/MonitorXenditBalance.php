<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\LowBalanceAdminNotification;
use App\Services\XenditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorXenditBalance extends Command
{
    protected $signature = 'xendit:monitor-balance';

    protected $description = 'Cek saldo Xendit harian dan bandingkan dengan kebutuhan disbursement yang akan datang (termasuk yang masih dalam window dispute)';

    /**
     * Buffer minimum — kita kasih jarak 20% di atas kebutuhan pasti,
     * supaya ada ruang aman untuk booking baru yang akan completed
     * dalam beberapa hari ke depan.
     */
    private const SAFETY_BUFFER_PERCENTAGE = 20;

    public function handle(): int
    {
        $xendit = app(XenditService::class);

        try {
            $balance = $xendit->getBalance();
        } catch (\Exception $e) {
            $this->error('Gagal mengecek saldo Xendit: ' . $e->getMessage());
            Log::error('Gagal cek saldo Xendit (monitoring harian)', ['error' => $e->getMessage()]);
            return self::FAILURE;
        }

        // Hitung total kebutuhan: semua booking yang BELUM di-disburse,
        // baik yang sudah lewat window dispute maupun yang masih dalam window
        // (supaya admin tahu proyeksi kebutuhan beberapa hari ke depan, bukan cuma hari ini).
        $totalPendingNeeded = Booking::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereIn('disbursement_status', ['pending', 'processing'])
            ->sum('host_earning');

        $safeThreshold = $totalPendingNeeded * (1 + self::SAFETY_BUFFER_PERCENTAGE / 100);

        $this->info("Saldo Xendit saat ini: Rp " . number_format($balance, 0, ',', '.'));
        $this->info("Total kebutuhan disbursement (pending + processing): Rp " . number_format($totalPendingNeeded, 0, ',', '.'));
        $this->info("Threshold aman (+ buffer 20%): Rp " . number_format($safeThreshold, 0, ',', '.'));

        if ($balance < $safeThreshold) {
            $this->warn('Saldo di bawah threshold aman! Mengirim notifikasi ke admin.');

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new LowBalanceAdminNotification($balance, (int) $safeThreshold));
            }

            Log::warning('Saldo Xendit di bawah threshold aman', [
                'balance'   => $balance,
                'threshold' => $safeThreshold,
            ]);
        } else {
            $this->info('Saldo masih dalam batas aman.');
        }

        return self::SUCCESS;
    }
}