<?php

namespace AnimeSite\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifySubscriptionExpiration extends Notification
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
            ->subject('Термін підписки закінчується')
            ->line('Ваша підписка скоро завершиться. Продовжіть її, щоб не втратити переваги.')
            ->action('Продовжити', url('/subscription'));
    }

    public function toArray($notifiable)
    {
        return ['type' => 'subscription_expiration'];
    }
}
