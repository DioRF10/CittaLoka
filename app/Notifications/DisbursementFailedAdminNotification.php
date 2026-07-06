<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DisbursementFailedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking, public string $reason)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Disbursement Gagal — ' . $this->booking->kode_booking)
            ->greeting('Halo Admin,')
            ->line('Disbursement untuk booking berikut gagal diproses oleh Xendit.')
            ->line('**Kode Booking:** ' . $this->booking->kode_booking)
            ->line('**Host:** ' . ($this->booking->host?->user?->name ?? '-'))
            ->line('**Nominal:** Rp ' . number_format($this->booking->host_earning, 0, ',', '.'))
            ->line('**Alasan Gagal:** ' . $this->reason)
            ->action('Lihat di Admin Panel', url('/admin/payouts'))
            ->line('Mohon segera ditinjau dan diproses secara manual jika perlu.');
    }

    public function toDatabase(object $notifiable): array
    {
        return \Filament\Notifications\Notification::make()
            ->title('Disbursement Gagal')
            ->body('Booking ' . $this->booking->kode_booking . ' gagal di-disburse: ' . $this->reason)
            ->icon('heroicon-o-x-circle')
            ->iconColor('danger')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('Lihat Payouts')
                    ->url(url('/admin/payouts'))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
