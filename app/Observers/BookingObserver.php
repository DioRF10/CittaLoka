<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\MemoryBook;

class BookingObserver
{
    /**
     * Triggered setelah Booking diupdate.
     * Jika status berubah menjadi 'completed', buat MemoryBook secara otomatis
     * (jika belum ada).
     */
    public function updated(Booking $booking): void
    {
        // Hanya proses jika status baru adalah 'completed'
        if ($booking->status !== 'completed') {
            return;
        }

        // Cek apakah status sebelumnya BUKAN completed (mencegah duplikasi)
        if ($booking->getOriginal('status') === 'completed') {
            return;
        }

        // Buat MemoryBook hanya jika belum ada untuk booking ini
        MemoryBook::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'judul'           => 'Memory Book untuk ' . $booking->user?->name,
                'status'          => 'pending_host',
                'host_message'    => null,
            ]
        );
    }
}
