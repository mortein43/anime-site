<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Anime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyNewSeasons extends Notification
{
    use Queueable;

    public function __construct(public Anime $anime) {}

    /**
     * Вказуємо канали доставки — додано 'database' для фронтенду
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Email повідомлення
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новий сезон аніме!')
            ->line("Вийшов новий сезон аніме '{$this->anime->name}'!")
            ->action('Дивитись', url('/animes/' . $this->anime->slug))
            ->line('Не пропустіть!');
    }

    /**
     * Дані для збереження у базу і відправки на фронтенд
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_season',
            'anime_id' => $this->anime->id,
            'anime_name' => $this->anime->name,
            'message' => "Вийшов новий сезон аніме «{$this->anime->name}»!",
            'url' => url('/animes/' . $this->anime->slug),
        ];
    }
}
