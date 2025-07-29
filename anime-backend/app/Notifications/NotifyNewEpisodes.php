<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Anime;
use AnimeSite\Models\Episode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyNewEpisodes extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Episode $episode,
        public Anime $anime
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * Додаємо 'database' для збереження у таблицю notifications
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Нова серія аніме: {$this->anime->name}")
            ->line("Вийшла нова серія: {$this->episode->name}")
            ->action('Переглянути епізод', url("/episodes/{$this->episode->slug}"));
    }

    /**
     * Get the array representation of the notification.
     *
     * Повертаємо дані для фронтенду
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'episode_id' => $this->episode->id,
            'episode_name' => $this->episode->name,
            'anime_id' => $this->anime->id,
            'anime_name' => $this->anime->name,
            'message' => "Вийшла нова серія «{$this->episode->name}» аніме «{$this->anime->name}»",
            'url' => url("/episodes/{$this->episode->slug}"),
        ];
    }
}
