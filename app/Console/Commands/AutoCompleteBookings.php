<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCompleteBookings extends Command
{
    protected $signature = 'bookings:complete';

    protected $description = 'Tandai booking confirmed sebagai completed setelah experience selesai + buffer waktu';

    /**
     * Buffer waktu (dalam jam) setelah jam selesai experience
     * sebelum booking otomatis ditandai "completed".
     * Ini memberi toleransi kalau experience berjalan lebih lama dari estimasi.
     */
    private const COMPLETION_BUFFER_HOURS = 2;

    public function handle(): int
    {
        $bookings = Booking::where('status', 'confirmed')
            ->where('payment_status', 'paid')
            ->whereNull('completed_at')
            ->with('experience')
            ->get()
            ->filter(function (Booking $booking) {
                return $this->isPastBufferTime($booking);
            });

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada booking yang siap di-auto-complete saat ini.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($bookings as $booking) {
            $booking->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);

            $count++;

            $this->info("Booking {$booking->kode_booking}: ditandai completed.");
            Log::info('Booking auto-completed', ['kode_booking' => $booking->kode_booking]);

            // TODO: trigger memory book (TriggerMemoryBooks logic bisa dipanggil di sini,
            // atau biarkan command memory-book:trigger berjalan terpisah dan cek status completed)
        }

        $this->info("Selesai. Total {$count} booking ditandai completed.");

        return self::SUCCESS;
    }

    /**
     * Cek apakah waktu selesai experience (tanggal + jam + durasi)
     * sudah melewati buffer yang ditentukan.
     */
    private function isPastBufferTime(Booking $booking): bool
    {
        if (! $booking->tanggal_experience || ! $booking->jam_experience) {
            return false;
        }

        // Gabungkan tanggal + jam mulai experience
        $startDateTime = Carbon::parse($booking->tanggal_experience->format('Y-m-d') . ' ' . $booking->jam_experience);

        // Tambahkan durasi experience (kalau ada relasinya), default 0 menit kalau tidak ada
        $durasiMenit = $booking->experience->durasi_menit ?? 0;
        $endDateTime = $startDateTime->copy()->addMinutes($durasiMenit);

        // Tambahkan buffer waktu sebelum dianggap benar-benar selesai
        $completionThreshold = $endDateTime->copy()->addHours(self::COMPLETION_BUFFER_HOURS);

        return now()->greaterThanOrEqualTo($completionThreshold);
    }
}