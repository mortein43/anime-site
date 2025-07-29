<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Anime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyPlannedReminders extends Notification
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
        return ['mail', 'database'];  // додаємо базу для фронтенду
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Нагадування: аніме у вашому списку «В планах»")
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Аніме «{$this->anime->name}» все ще у вашому списку «В планах». Час розпочати перегляд!")
            ->action('Переглянути аніме', url('/animes/' . $this->anime->slug))
            ->line('Насолоджуйтеся переглядом!');
    }

    /**
     * Дані для збереження у базу і для фронтенду
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'planned_reminder',
            'anime_id' => $this->anime->id,
            'anime_name' => $this->anime->name,
            'message' => "Аніме «{$this->anime->name}» все ще у вашому списку «В планах». Час розпочати перегляд!",
            'url' => url('/animes/' . $this->anime->slug),
            // Якщо маєш дату закінчення, додай сюди 'expires_at'
        ];
    }
}
