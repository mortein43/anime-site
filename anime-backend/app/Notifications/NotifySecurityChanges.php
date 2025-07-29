<?php

namespace AnimeSite\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifySecurityChanges extends Notification
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
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Безпекове оновлення')
            ->line('Ваш обліковий запис щойно зазнав змін безпеки.')
            ->action('Перевірити', url('/settings/security'));
    }

    public function toArray($notifiable)
    {
        return ['type' => 'security_change'];
    }
}
