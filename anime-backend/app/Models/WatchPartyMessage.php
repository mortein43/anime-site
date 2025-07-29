<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\WatchPartyMessageQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

/**
 * @mixin IdeHelperWatchPartyMessage
 */
class WatchPartyMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'watch_party_id',
        'user_id',
        'message',
    ];

    protected $casts = [
        'id' => 'string',
        'watch_party_id' => 'string',
        'user_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::ulid();
            }
        });
    }

    // Relationships
    public function watchParty(): BelongsTo
    {
        return $this->belongsTo(WatchParty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    public function getIsFromHostAttribute(): bool
    {
        return $this->user_id === $this->watchParty->host_user_id;
    }

    // Scopes
    public function scopeForWatchParty(Builder $query, string $watchPartyId): Builder
    {
        return $query->where('watch_party_id', $watchPartyId);
    }

    public function scopeFromUser(Builder $query, string $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent(Builder $query, int $minutes = 60): Builder
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'asc');
    }

    public function newEloquentBuilder($query): WatchPartyMessageQueryBuilder
    {
        return new WatchPartyMessageQueryBuilder($query);
    }
}
