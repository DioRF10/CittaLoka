<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowBalanceAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public int $currentBalance, public int $requiredAmount)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Saldo Xendit Rendah')
            ->greeting('Halo Admin,')
            ->line('Saldo akun Xendit CittaLoka saat ini berada di bawah kebutuhan untuk disbursement yang akan diproses.')
            ->line('**Saldo Saat Ini:** Rp ' . number_format($this->currentBalance, 0, ',', '.'))
            ->line('**Kebutuhan untuk Disbursement Pending:** Rp ' . number_format($this->requiredAmount, 0, ',', '.'))
            ->line('**Kekurangan:** Rp ' . number_format($this->requiredAmount - $this->currentBalance, 0, ',', '.'))
            ->action('Buka Dashboard Xendit', 'https://dashboard.xendit.co')
            ->line('Mohon segera lakukan top-up agar disbursement ke host tidak terhambat.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'low_balance_admin',
            'title'   => 'Saldo Xendit Rendah',
            'message' => 'Saldo Rp ' . number_format($this->currentBalance, 0, ',', '.') . ' tidak cukup untuk disbursement pending (butuh Rp ' . number_format($this->requiredAmount, 0, ',', '.') . ').',
            'url'     => 'https://dashboard.xendit.co',
        ];
    }
}