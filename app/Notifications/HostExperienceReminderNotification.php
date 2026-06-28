<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HostExperienceReminderNotification extends Notification
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
            ->subject('Besok Ada Sesi! — ' . $this->booking->experience_title_snapshot)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Ini pengingat bahwa kamu punya sesi experience besok.')
            ->line('**' . $this->booking->experience_title_snapshot . '**')
            ->line('Tanggal: ' . \Carbon\Carbon::parse($this->booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY'))
            ->line('Jam: ' . \Carbon\Carbon::parse($this->booking->jam_experience)->format('H:i') . ' WITA')
            ->line('Wisatawan: ' . $this->booking->user->name . ' (' . $this->booking->jumlah_peserta . ' peserta)')
            ->action('Lihat Detail Booking', route('host.bookings.detail', $this->booking->id))
            ->line('Siapkan yang terbaik untuk wisatawanmu!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'host_experience_reminder',
            'kode_booking' => $this->booking->kode_booking,
            'title'        => 'Besok Ada Sesi!',
            'message'      => '"' . $this->booking->experience_title_snapshot . '" besok dengan ' . $this->booking->jumlah_peserta . ' peserta.',
            'url'          => route('host.bookings.detail', $this->booking->id),
        ];
    }
}
