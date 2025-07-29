<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Anime;
use AnimeSite\Models\Episode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyEpisodeDateChanges extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Episode $episode,
        protected Anime $anime,
        protected ?string $oldDate
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
            ->subject("Оновлення дати виходу серії: {$this->anime->name}")
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Дата виходу епізоду «{$this->episode->name}» змінилась.")
            ->line("Стара дата: {$this->oldDate}")
            ->line("Нова дата: {$this->episode->air_date}")
            ->action('Переглянути епізод', url("/episodes/{$this->episode->slug}"))
            ->line('Дякуємо, що залишаєтесь з нами!');
    }

    /**
     * Get the array representation of the notification.
     *
     * Дані для збереження в таблиці і передачі на фронтенд
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
            'old_air_date' => $this->oldDate,
            'new_air_date' => $this->episode->air_date,
            'message' => "Дата виходу епізоду «{$this->episode->name}» змінилась з {$this->oldDate} на {$this->episode->air_date}.",
            'url' => url("/episodes/{$this->episode->slug}"),
        ];
    }
}
