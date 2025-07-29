<?php

namespace AnimeSite\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifySubscriptionRenewal extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $tariffName) {}

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
            ->subject('Зміна тарифу')
            ->line("Відбулись зміни у тарифному плані '{$this->tariffName}'")
            ->action('Детальніше', url('/pricing'));
    }

    public function toArray($notifiable)
    {
        return ['type' => 'tariff_change', 'tariff' => $this->tariffName];
    }
}
