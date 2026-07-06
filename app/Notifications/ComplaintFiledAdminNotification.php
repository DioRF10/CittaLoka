<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintFiledAdminNotification extends Notification
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

        return (new MailMessage)
            ->subject('⚠️ Complaint Baru — ' . $booking->kode_booking)
            ->greeting('Halo Admin,')
            ->line('Ada complaint baru yang perlu ditinjau.')
            ->line('**Kode Booking:** ' . $booking->kode_booking)
            ->line('**Diajukan oleh:** ' . $this->complaint->getFiledByRoleLabel() . ' — ' . $this->complaint->filedBy->name)
            ->line('**Kategori:** ' . $this->complaint->getCategoryLabel())
            ->line('**Deskripsi:** ' . \Illuminate\Support\Str::limit($this->complaint->description, 200))
            ->action('Tinjau di Admin Panel', url('/admin/complaints/' . $this->complaint->id))
            ->line('Mohon segera ditinjau.');
    }

    public function toDatabase(object $notifiable): array
    {
        $booking = $this->complaint->booking;

        return \Filament\Notifications\Notification::make()
            ->title('Complaint Baru')
            ->body($this->complaint->getFiledByRoleLabel() . ' mengajukan complaint untuk booking ' . $booking->kode_booking . ' — ' . $this->complaint->getCategoryLabel())
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('danger')
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('Lihat Complaint')
                    ->url(url('/admin/complaints/' . $this->complaint->id))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
