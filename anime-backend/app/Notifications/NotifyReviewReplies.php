<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyReviewReplies extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $reviewTitle, public string $animeTitle) {}

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
            ->subject('Нова відповідь на ваш відгук')
            ->line("Хтось відповів на ваш відгук: '{$this->reviewTitle}'")
            ->line("Аніме: {$this->animeTitle}")
            ->action('Переглянути', url('/reviews'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'review_reply',
            'title' => $this->reviewTitle,
            'anime' => $this->animeTitle
        ];
    }
}
