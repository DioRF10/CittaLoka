<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\ExperienceReminderNotification;
use App\Notifications\HostExperienceReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendExperienceReminders extends Command
{
    protected $signature = 'bookings:reminder';

    protected $description = 'Kirim reminder H-1 ke traveler dan host untuk booking yang experience-nya besok';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $bookings = Booking::where('status', 'confirmed')
            ->whereDate('tanggal_experience', $tomorrow)
            ->with(['user', 'host.user'])
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada booking dengan experience besok.');
            return self::SUCCESS;
        }

        foreach ($bookings as $booking) {
            try {
                $booking->user?->notify(new ExperienceReminderNotification($booking));
                sleep(1);
                $booking->host?->user?->notify(new HostExperienceReminderNotification($booking));
                sleep(1);
            } catch (\Exception $e) {
                Log::error("Email error untuk booking {$booking->kode_booking}: " . $e->getMessage());
            }

            $this->info("Reminder dikirim untuk booking {$booking->kode_booking}.");
            Log::info('Reminder H-1 dikirim', ['kode_booking' => $booking->kode_booking]);
        }

        $this->info("Selesai. Total {$bookings->count()} booking diberi reminder.");

        return self::SUCCESS;
    }
}