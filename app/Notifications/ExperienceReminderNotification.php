<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExperienceReminderNotification extends Notification
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
            ->subject('Besok Experience-mu! — ' . $this->booking->experience_title_snapshot)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Ini pengingat bahwa experience-mu akan berlangsung besok.')
            ->line('**' . $this->booking->experience_title_snapshot . '**')
            ->line('Tanggal: ' . \Carbon\Carbon::parse($this->booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY'))
            ->line('Jam: ' . \Carbon\Carbon::parse($this->booking->jam_experience)->format('H:i') . ' WITA')
            ->line('Lokasi: ' . $this->booking->location_snapshot)
            ->line('Host: ' . $this->booking->host_name_snapshot)
            ->action('Lihat Detail Booking', route('bookings.show', $this->booking->kode_booking))
            ->line('Sampai jumpa besok!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'experience_reminder',
            'kode_booking' => $this->booking->kode_booking,
            'title'        => 'Besok Experience-mu!',
            'message'      => '"' . $this->booking->experience_title_snapshot . '" besok pukul ' . \Carbon\Carbon::parse($this->booking->jam_experience)->format('H:i') . ' WITA.',
            'url'          => route('bookings.show', $this->booking->kode_booking),
        ];
    }
}
