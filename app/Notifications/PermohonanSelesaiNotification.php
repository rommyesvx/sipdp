<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanSelesaiNotification extends Notification
{
    use Queueable;
    protected $permohonan;

    /**
     * Create a new notification instance.
     */
    public function __construct($permohonan)
    {
        //
        $this->permohonan = $permohonan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Permohonan Anda Telah Selesai')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Permohonan data Anda dengan tujuan "' . $this->permohonan->tujuan . '" telah diproses dan selesai.')
            ->action('Lihat Detail', url('/permohonan/' . $this->permohonan->id))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
