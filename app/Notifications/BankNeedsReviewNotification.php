<?php

namespace App\Notifications;

use App\Models\Host;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BankNeedsReviewNotification extends Notification
{
    use Queueable;

    public function __construct(public Host $host)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rekening Perlu Ditinjau')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Kami sedang meninjau data rekening yang kamu daftarkan.')
            ->line('Ini tidak menghambat aktivitasmu di CittaLoka — kamu tetap bisa membuat dan mengelola experience seperti biasa.')
            ->line('Proses peninjauan biasanya selesai dalam 1-2 hari kerja, dan hanya akan mempengaruhi waktu pencairan dana booking pertamamu.')
            ->action('Buka Dashboard', route('host.dashboard'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'bank_needs_review',
            'title'   => 'Rekening Perlu Ditinjau',
            'message' => 'Tim kami sedang meninjau data rekeningmu.',
            'url'     => route('host.settings'),
        ];
    }
}
