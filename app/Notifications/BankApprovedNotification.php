<?php

namespace App\Notifications;

use App\Models\Host;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BankApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Host $host)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rekening Kamu Sudah Terverifikasi ✅')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Rekening yang kamu daftarkan sudah berhasil diverifikasi oleh tim kami.')
            ->line('Kamu sekarang siap menerima pencairan dana dari booking yang kamu terima.')
            ->action('Buka Dashboard', route('host.dashboard'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'bank_approved',
            'title'   => 'Rekening Terverifikasi',
            'message' => 'Rekeningmu sudah diverifikasi, siap menerima pembayaran.',
            'url'     => route('host.settings'),
        ];
    }
}
