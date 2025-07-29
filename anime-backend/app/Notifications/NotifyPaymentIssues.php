<?php

namespace AnimeSite\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyPaymentIssues extends Notification
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
            ->subject('Проблема з оплатою')
            ->line('Виникла проблема з обробкою вашого платежу.')
            ->action('Перевірити платіж', url('/billing'));
    }

    public function toArray($notifiable)
    {
        return ['type' => 'payment_issue'];
    }
}
