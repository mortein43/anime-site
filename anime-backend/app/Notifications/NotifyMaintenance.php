<?php

namespace AnimeSite\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyMaintenance extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly \DateTimeInterface $startAt,
        public readonly ?string $note = null
    ){}

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
            ->subject('Заплановане техобслуговування')
            ->line('Сайт буде тимчасово недоступний з ' . $this->startAt->format('d.m.Y H:i'))
            ->line($this->note ?? '')
            ->line('Дякуємо за розуміння!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'maintenance',
            'start_at' => $this->startAt->format(DATE_ATOM),
            'note' => $this->note,
        ];
    }
}
