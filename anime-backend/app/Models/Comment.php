<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\CommentQueryBuilder;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Selection;
use AnimeSite\Models\Traits\HasUserInteractions;
use AnimeSite\Notifications\NotifyCommentReplies;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use AnimeSite\Builders\CommentBuilder;

/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory,  HasUlids, HasUserInteractions;

    protected $casts = [
        'is_like' => 'boolean',
    ];
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        // Якщо commentable_type є Comment, то це відповідь на коментар
        return $this->belongsTo(self::class, 'commentable_id')
                    ->where('commentable_type', self::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anime()
    {
        return $this->morphTo()->where('commentable_type', Anime::class);
    }

    /**
     * Допоміжний зв’язок до епізоду (якщо commentable_type = Episode::class)
     */
    public function episode()
    {
        return $this->morphTo()->where('commentable_type', Episode::class);
    }

    /**
     * Допоміжний зв’язок до добірки (якщо commentable_type = Collection::class)
     */
    public function selection()
    {
        return $this->morphTo()->where('commentable_type', Selection::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class)->chaperone();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CommentReport::class)->chaperone();
    }

    public function isRoot(): bool
    {
        return $this->commentable_type !== self::class;
    }

    public function childrenCount(): int
    {
        return $this->children()->count();
    }

    public function children(): HasMany
    {
        // Повертаємо коментарі, де commentable_type є Comment і commentable_id є ID цього коментаря
        return $this->hasMany(self::class, 'commentable_id')
                    ->where('commentable_type', self::class);
    }

    public function getTranslatedTypeAttribute(): string
    {
        return match ($this->commentable_type) {
            Anime::class => 'Аніме',
            Episode::class => 'Епізод',
            Selection::class => 'Підбірка',
            Comment::class => 'Коментар',
            default => 'Невідомий контент',
        };
    }

    public function excerpt(int $length = 50): string
    {
        return str()->limit($this->body, $length);
    }

    protected function isReply(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->commentable_type === self::class
        );
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => trim($value)
        );
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return CommentQueryBuilder
     */
    public function newEloquentBuilder($query): CommentQueryBuilder
    {
        return new CommentQueryBuilder($query);
    }

    protected static function booted(): void
    {
        static::created(function (Comment $comment) {
            // Якщо це відповідь (commentable_type = Comment)
            if ($comment->commentable_type !== Comment::class) {
                return; // Не відповіді — пропускаємо
            }

            $parentComment = $comment->commentable; // батьківський коментар
            $replyUser = $comment->user;            // той, хто написав відповідь
            $parentUser = $parentComment->user;     // автор батьківського коментаря

            // Не надсилати собі сповіщення
            if ($replyUser->id === $parentUser->id) {
                return;
            }

            // Перевірка, чи увімкнені сповіщення про відповіді
            if (! $parentUser->notify_comment_replies) {
                return;
            }

            // Надсилаємо нотифікацію
            $parentUser->notify(new NotifyCommentReplies($comment, $parentComment));
        });
    }
}
