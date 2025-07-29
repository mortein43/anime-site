<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Anime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyStatusChanges extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Anime $anime) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // додаємо базу для фронтенду
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Статус аніме '{$this->anime->name}' змінено")
            ->line("Статус аніме **{$this->anime->name}** було змінено на `{$this->anime->status->name()}`.")
            ->action('Переглянути аніме', url('/animes/' . $this->anime->slug))
            ->line('Дякуємо, що користуєтесь нашим сайтом!');
    }

    /**
     * Дані для збереження у базу і для фронтенду
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'status_change',
            'anime_id' => $this->anime->id,
            'anime_name' => $this->anime->name,
            'status' => $this->anime->status->name(),
            'message' => "Статус аніме «{$this->anime->name}» було змінено на «{$this->anime->status->name()}».",
            'url' => url('/animes/' . $this->anime->slug),
        ];
    }
}
