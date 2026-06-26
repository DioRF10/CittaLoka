<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class RefundCalculator
{
    /**
     * Hitung persentase refund berdasarkan kapan cancel dilakukan,
     * relatif terhadap tanggal+jam experience.
     *
     * Aturan (dari rancangan pembayaran CittaLoka):
     *   > 7 hari sebelum   = 100%
     *   3–7 hari sebelum   = 50%
     *   1–3 hari sebelum   = 25%
     *   < 24 jam / no-show = 0%
     *
     * @return int Persentase refund (0, 25, 50, atau 100)
     */
    public function calculatePercentage(Booking $booking, ?Carbon $cancelledAt = null): int
    {
        $cancelledAt = $cancelledAt ?? now();

        $experienceDateTime = Carbon::parse(
            $booking->tanggal_experience->format('Y-m-d') . ' ' . $booking->jam_experience
        );

        $hoursUntilExperience = $cancelledAt->diffInHours($experienceDateTime, false);

        if ($hoursUntilExperience < 0) {
            // Sudah lewat waktu experience — dianggap no-show
            return 0;
        }

        if ($hoursUntilExperience >= (7 * 24)) {
            return 100;
        }

        if ($hoursUntilExperience >= (3 * 24)) {
            return 50;
        }

        if ($hoursUntilExperience >= 24) {
            return 25;
        }

        return 0;
    }

    /**
     * Hitung nominal refund (dalam Rupiah) berdasarkan persentase.
     */
    public function calculateAmount(Booking $booking, int $percentage): int
    {
        return (int) round($booking->total_harga * ($percentage / 100));
    }

    /**
     * Helper deskriptif untuk ditampilkan ke user sebelum mereka konfirmasi cancel.
     */
    public function getPolicyDescription(Booking $booking): string
    {
        $percentage = $this->calculatePercentage($booking);
        $amount = $this->calculateAmount($booking, $percentage);

        if ($percentage === 100) {
            return "Anda akan menerima refund penuh (100%) sebesar Rp " . number_format($amount, 0, ',', '.') . ".";
        }

        if ($percentage === 0) {
            return "Karena pembatalan dilakukan kurang dari 24 jam sebelum experience, tidak ada refund yang akan diberikan.";
        }

        return "Anda akan menerima refund sebesar {$percentage}% (Rp " . number_format($amount, 0, ',', '.') . ") sesuai kebijakan pembatalan.";
    }
}