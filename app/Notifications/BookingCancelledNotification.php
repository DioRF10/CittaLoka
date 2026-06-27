<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification
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
            ->subject('Booking Dibatalkan — ' . $this->booking->kode_booking)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Booking berikut telah dibatalkan oleh wisatawan.')
            ->line('**' . $this->booking->experience_title_snapshot . '**')
            ->line('Tanggal: ' . \Carbon\Carbon::parse($this->booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY'))
            ->when($this->booking->cancel_reason, fn ($mail) => $mail->line('Alasan: ' . $this->booking->cancel_reason))
            ->action('Lihat Detail', route('host.bookings.index'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'booking_cancelled',
            'kode_booking'   => $this->booking->kode_booking,
            'title'          => 'Booking Dibatalkan',
            'message'        => 'Booking "' . $this->booking->experience_title_snapshot . '" telah dibatalkan.',
            'url'            => route('host.bookings.index'),
        ];
    }
}
