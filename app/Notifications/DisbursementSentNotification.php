<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DisbursementSentNotification extends Notification
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
        $host = $this->booking->host;
        $bankLabel = $host->bank_name . ' ****' . ($host->bank_account_last4 ?? substr($host->bank_account_number, -4));

        return (new MailMessage)
            ->subject('Dana Sudah Dikirim — ' . $this->booking->kode_booking)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Dana dari booking berikut sudah kami kirim ke rekeningmu.')
            ->line('**' . $this->booking->experience_title_snapshot . '**')
            ->line('Nominal: Rp ' . number_format($this->booking->host_earning, 0, ',', '.'))
            ->line('Rekening tujuan: ' . $bankLabel)
            ->action('Lihat Riwayat Pendapatan', route('host.earnings'))
            ->line('Terima kasih sudah menjadi host CittaLoka!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'disbursement_sent',
            'kode_booking'   => $this->booking->kode_booking,
            'title'          => 'Dana Sudah Dikirim',
            'message'        => 'Rp ' . number_format($this->booking->host_earning, 0, ',', '.') . ' sudah masuk ke rekeningmu.',
            'url'            => route('host.earnings'),
        ];
    }
}
