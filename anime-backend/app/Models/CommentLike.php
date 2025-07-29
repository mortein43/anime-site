<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\CommentLikeQueryBuilder;
use AnimeSite\Notifications\NotifyCommentLikes;
use Database\Factories\CommentLikeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use AnimeSite\Builders\CommentLikeBuilder;

/**
 * @mixin IdeHelperCommentLike
 */
class CommentLike extends Model
{
    /** @use HasFactory<CommentLikeFactory> */
    use HasFactory, HasUlids;

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return CommentLikeQueryBuilder
     */
    public function newEloquentBuilder($query): CommentLikeQueryBuilder
    {
        return new CommentLikeQueryBuilder($query);
    }

    protected static function booted(): void
    {
        static::created(function (CommentLike $like) {
            if (! $like->is_liked) {
                return; // Пропускаємо дизлайки
            }

            $comment = $like->comment;
            $fromUser = $like->user;
            $toUser = $comment->user;

            // Не надсилаємо нотифікацію самому собі
            if ($fromUser->id === $toUser->id) {
                return;
            }

            // Перевіряємо, чи користувач увімкнув нотифікації
            if (! $toUser->notify_comment_likes) {
                return;
            }

            // Надсилаємо нотифікацію
            // $toUser->notify(new NotifyCommentLikes($comment, $fromUser));
            if (app()->environment('production')) {
    $toUser->notify(new NotifyCommentLikes($comment, $fromUser));
}
        });
    }
}
