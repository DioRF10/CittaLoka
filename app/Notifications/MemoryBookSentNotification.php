<?php

namespace App\Notifications;

use App\Models\MemoryBook;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemoryBookSentNotification extends Notification
{
    use Queueable;

    public function __construct(public MemoryBook $memoryBook)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->memoryBook->booking;

        return (new MailMessage)
            ->subject('Memory Book Kamu Sudah Siap! 📖')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line($booking->host_name_snapshot . ' telah mengirimkan kenangan dari experience yang kamu jalani.')
            ->line('**' . $booking->experience_title_snapshot . '**')
            ->action('Lihat Memory Book', route('memory-book.show', $booking->kode_booking))
            ->line('Simpan kenangan ini dan bagikan kebahagiaanmu!');
    }

    public function toArray(object $notifiable): array
    {
        $booking = $this->memoryBook->booking;

        return [
            'type'         => 'memory_book_sent',
            'kode_booking' => $booking->kode_booking,
            'title'        => 'Memory Book Kamu Sudah Siap!',
            'message'      => $booking->host_name_snapshot . ' mengirimkan kenangan dari "' . $booking->experience_title_snapshot . '".',
            'url'          => route('memory-book.show', $booking->kode_booking),
        ];
    }
}
