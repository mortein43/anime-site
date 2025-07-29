<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Anime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAnnouncementToOngoing extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Anime $anime
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * Додаємо 'database', щоб зберегти у таблицю notifications
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
            ->subject("Аніме «{$this->anime->name}» тепер у статусі: Онґоїнг!")
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Аніме «{$this->anime->name}» перейшло у статус онґоїнг.")
            ->action('Дивитись аніме', url("/animes/{$this->anime->slug}"))
            ->line('Не пропусти нові епізоди!');
    }

    /**
     * Get the array representation of the notification.
     * Ці дані збережуться в таблиці notifications і будуть повертатися фронтенду
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'anime_id' => $this->anime->id,
            'anime_name' => $this->anime->name,
            'message' => "Аніме «{$this->anime->name}» тепер у статусі: Онґоїнг!",
            'url' => url("/animes/{$this->anime->slug}"),
        ];
    }
}
