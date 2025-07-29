<?php

namespace AnimeSite\Notifications;

use AnimeSite\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyCommentReplies extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Comment $replyComment,  // новий коментар-відповідь
        public Comment $parentComment,
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
            ->subject('У вас нова відповідь на коментар')
            ->greeting("Привіт, {$notifiable->name}!")
            ->line("Користувач {$this->replyComment->user->name} відповів на ваш коментар:")
            ->line("\"{$this->parentComment->body}\"")
            ->action('Переглянути відповідь', url("/comments/{$this->replyComment->id}"))
            ->line('Дякуємо, що залишаєтесь з нами!');
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
            'reply_comment_id' => $this->replyComment->id,
            'reply_comment_body' => $this->replyComment->body,
            'parent_comment_id' => $this->parentComment->id,
            'parent_comment_body' => $this->parentComment->body,
            'reply_user_name' => $this->replyComment->user->name,
            'message' => "Користувач {$this->replyComment->user->name} відповів на ваш коментар.",
            'url' => url("/comments/{$this->replyComment->id}"),
        ];
    }
}
