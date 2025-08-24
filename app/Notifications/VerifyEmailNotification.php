<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends VerifyEmailBase
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Method verificationUrl() sudah disediakan oleh class yang kita extend
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
                    ->subject('Verifikasi Alamat Email Anda - SIPDP')
                    ->line('Terima kasih telah mendaftar! Mohon klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
                    ->action('Verifikasi Alamat Email', $verificationUrl)
                    ->line('Jika Anda tidak membuat akun ini, Anda bisa mengabaikan email ini.');
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
