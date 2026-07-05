<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintResolvedNotification extends Notification
{
    use Queueable;

    public function __construct(public Complaint $complaint)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->complaint->booking;
        $isResolved = $this->complaint->status === 'resolved';

        return (new MailMessage)
            ->subject(($isResolved ? '✓ Complaint Terselesaikan' : 'Update Complaint') . ' — ' . $booking->kode_booking)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Complaint yang kamu ajukan untuk booking ' . $booking->kode_booking . ' sudah ditinjau tim CittaLoka.')
            ->line('**Status:** ' . $this->complaint->getStatusLabel())
            ->when($this->complaint->resolution_notes, fn ($mail) => $mail->line('**Catatan dari tim:** ' . $this->complaint->resolution_notes))
            ->action('Lihat Detail Booking', url('/bookings/' . $booking->kode_booking));
    }

    public function toArray(object $notifiable): array
    {
        $booking = $this->complaint->booking;

        return [
            'type' => 'complaint_resolved',
            'kode_booking' => $booking->kode_booking,
            'title' => 'Update Complaint',
            'message' => 'Complaint booking ' . $booking->kode_booking . ' sekarang: ' . $this->complaint->getStatusLabel(),
            'url' => url('/bookings/' . $booking->kode_booking),
        ];
    }
}
