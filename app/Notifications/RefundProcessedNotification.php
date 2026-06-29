<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundProcessedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Refund Diproses — ' . $this->booking->kode_booking)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Refund untuk booking yang kamu batalkan sudah berhasil kami proses.')
            ->line('**' . $this->booking->experience_title_snapshot . '**')
            ->line('Nominal refund: Rp ' . number_format($this->booking->refund_amount, 0, ',', '.') . ' (' . $this->booking->refund_percentage . '%)')
            ->action('Lihat Detail Booking', route('bookings.show', $this->booking->kode_booking))
            ->line('Terima kasih atas pengertiannya.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'refund_processed',
            'kode_booking'   => $this->booking->kode_booking,
            'title'          => 'Refund Diproses',
            'message'        => 'Refund Rp ' . number_format($this->booking->refund_amount, 0, ',', '.') . ' sudah dikirim.',
            'url'            => route('bookings.show', $this->booking->kode_booking, false),
        ];
    }
}
