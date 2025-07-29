<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Comment;
use AnimeSite\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyCommentLikes extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Comment $comment,
        public User $fromUser
    ) {}

    /**
     * Get the notification's delivery channels.
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
            ->subject('Ваш коментар отримав лайк')
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Користувач {$this->fromUser->name} лайкнув ваш коментар:")
            ->line("\"{$this->comment->body}\"")
            ->action('Переглянути коментар', url("/comments/{$this->comment->id}"))
            ->line('Дякуємо, що берете участь у спільноті!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'comment_body' => $this->comment->body,
            'from_user_id' => $this->fromUser->id,
            'from_user_name' => $this->fromUser->name,
            'message' => "Користувач {$this->fromUser->name} лайкнув ваш коментар",
            'url' => url("/comments/{$this->comment->id}"),
        ];
    }
}
