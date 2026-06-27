<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification
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
            ->subject('Booking Dikonfirmasi — ' . $this->booking->kode_booking)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Pembayaran kamu untuk experience berikut sudah kami terima.')
            ->line('**' . $this->booking->experience_title_snapshot . '**')
            ->line('Tanggal: ' . \Carbon\Carbon::parse($this->booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY') . ' pukul ' . \Carbon\Carbon::parse($this->booking->jam_experience)->format('H:i') . ' WITA')
            ->line('Host: ' . $this->booking->host_name_snapshot)
            ->action('Lihat Detail Booking', route('bookings.show', $this->booking->kode_booking))
            ->line('Sampai jumpa di Bali!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'booking_confirmed',
            'kode_booking'   => $this->booking->kode_booking,
            'title'          => 'Booking Dikonfirmasi',
            'message'        => 'Pembayaran untuk "' . $this->booking->experience_title_snapshot . '" berhasil dikonfirmasi.',
            'url'            => route('bookings.show', $this->booking->kode_booking),
        ];
    }
}
