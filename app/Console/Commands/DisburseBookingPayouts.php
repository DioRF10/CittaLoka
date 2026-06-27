<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\DisbursementFailedAdminNotification;
use App\Services\XenditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DisburseBookingPayouts extends Command
{
    protected $signature = 'bookings:disburse';

    protected $description = 'Kirim disbursement Xendit ke host untuk booking completed yang sudah lewat window dispute (48 jam) dan belum di-payout';

    /**
     * Window dispute — jumlah jam setelah booking completed
     * sebelum dana boleh dicairkan ke host.
     */
    private const DISPUTE_WINDOW_HOURS = 48;

    public function handle(): int
    {
        $bookings = Booking::where('status', 'completed')
            ->where('payment_status', 'paid')
            ->where('disbursement_status', 'pending')
            ->whereNotNull('completed_at')
            ->where('completed_at', '<=', now()->subHours(self::DISPUTE_WINDOW_HOURS))
            ->whereHas('host', function ($q) {
                $q->where('bank_review_status', 'verified');
            })
            ->with('host')
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada booking yang siap di-disburse saat ini.');
            return self::SUCCESS;
        }

        $xendit = app(XenditService::class);

        foreach ($bookings as $booking) {
            $host = $booking->host;

            if (! $host->bank_name || ! $host->bank_account_number) {
                $this->warn("Booking {$booking->kode_booking}: data bank host belum lengkap, skip.");
                continue;
            }

            try {
                $result = $xendit->createDisbursement(
                    externalId: 'disb-' . $booking->id,
                    bankCode: $host->bank_name,
                    accountNumber: $host->bank_account_number,
                    accountHolderName: $host->bank_account_holder ?? $host->bank_account_name,
                    amount: (int) $booking->host_earning,
                    description: 'Payout CittaLoka booking ' . $booking->kode_booking,
                );

                $booking->update([
                    'xendit_disbursement_id' => $result['id'] ?? null,
                    'disbursement_status' => 'processing',
                    'disbursement_failure_reason' => null,
                ]);

                $this->info("Booking {$booking->kode_booking}: disbursement dikirim.");
                Log::info('Disbursement dikirim', ['kode_booking' => $booking->kode_booking]);
            } catch (\Exception $e) {
                $booking->update([
                    'disbursement_status' => 'failed',
                    'disbursement_failure_reason' => $e->getMessage(),
                ]);

                $this->error("Booking {$booking->kode_booking}: gagal — {$e->getMessage()}");
                Log::error('Disbursement gagal', [
                    'kode_booking' => $booking->kode_booking,
                    'error' => $e->getMessage(),
                ]);

                // ── Notifikasi ke semua admin ──
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new DisbursementFailedAdminNotification($booking, $e->getMessage()));
                }
            }
        }

        return self::SUCCESS;
    }
}