<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Baru! — ' . $this->booking->kode_booking)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Kamu mendapat booking baru untuk experience-mu.')
            ->line('**' . $this->booking->experience_title_snapshot . '**')
            ->line('Tanggal: ' . \Carbon\Carbon::parse($this->booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY') . ' pukul ' . \Carbon\Carbon::parse($this->booking->jam_experience)->format('H:i') . ' WITA')
            ->line('Jumlah peserta: ' . $this->booking->jumlah_peserta . ' orang')
            ->line('Pendapatan kamu dari booking ini: Rp ' . number_format($this->booking->host_earning, 0, ',', '.'))
            ->action('Lihat Detail Booking', route('host.bookings.index'))
            ->line('Selamat menyambut wisatawan!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'new_booking_received',
            'kode_booking'   => $this->booking->kode_booking,
            'title'          => 'Booking Baru!',
            'message'        => $this->booking->jumlah_peserta . ' peserta booking "' . $this->booking->experience_title_snapshot . '".',
            'url'            => route('host.bookings.index'),
        ];
    }
}
