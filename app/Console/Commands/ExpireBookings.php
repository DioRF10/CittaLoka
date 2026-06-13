<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\ExperienceAvailability;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpireBookings extends Command
{
    protected $signature   = 'bookings:expire';
    protected $description = 'Expire pending bookings that have passed their payment deadline';

    public function handle(): void
    {
        // Expire booking yang pending_payment lebih dari 1 jam
        $expiredBookings = Booking::where('status', 'pending_payment')
            ->where('created_at', '<', now()->subHour())
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status'         => 'expired',
                'payment_status' => 'expired',
                'cancelled_at'   => now(),
                'cancel_reason'  => 'Payment deadline exceeded',
            ]);

            // Kembalikan slot
            $availability = ExperienceAvailability::find($booking->availability_id);
            if ($availability) {
                $availability->decrement('booked_slot', $booking->jumlah_peserta);
            }

            $this->info("Expired booking: {$booking->kode_booking}");
        }

        // Tandai booking sebagai completed kalau tanggal experience sudah lewat
        $completedBookings = Booking::where('status', 'confirmed')
            ->where('tanggal_experience', '<', now()->toDateString())
            ->get();

        foreach ($completedBookings as $booking) {
            $booking->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);

            $this->info("Completed booking: {$booking->kode_booking}");
        }

        $this->info("Done. Expired: {$expiredBookings->count()}, Completed: {$completedBookings->count()}");
    }
}