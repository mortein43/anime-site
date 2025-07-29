<?php

namespace AnimeSite\Models;

use AnimeSite\Builders\WatchPartyBuilder;
use AnimeSite\Enums\WatchPartyStatus;
use AnimeSite\Models\Builders\WatchPartyQueryBuilder;
use AnimeSite\Models\Traits\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperWatchParty
 */
class WatchParty extends Model
{
    use HasFactory, HasSeo, HasUlids;

    protected $table = 'watch_parties';
    protected $fillable = [
        'id',
        'name',
        'slug',
        'user_id',
        'episode_id',
        'is_private',
        'watch_party_status',
        'password',
        'max_viewers',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'watch_party_status' => WatchPartyStatus::class,
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::ulid();
            }
        });
    }
    public function newEloquentBuilder($query): WatchPartyQueryBuilder
    {
        return new WatchPartyQueryBuilder($query);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отримати епізод, який переглядається в кімнаті.
     */
    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    /**
     * Отримати глядачів кімнати.
     */
    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'watch_party_user')
            ->withPivot('joined_at', 'left_at')
            ->withTimestamps();
    }

    /**
     * Отримати активних глядачів кімнати.
     */
    public function activeViewers(): BelongsToMany
    {
        return $this->viewers()->whereNull('watch_party_user.left_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(WatchPartyMessage::class, 'watch_party_id', 'id');
    }

    /**
     * Перевірити, чи кімната активна.
     */
    public function isActive(): bool
    {
        return $this->watch_party_status === WatchPartyStatus::ACTIVE;
    }

    /**
     * Перевірити, чи кімната завершена.
     */
    public function isEnded(): bool
    {
        return $this->watch_party_status === WatchPartyStatus::ENDED;
    }

    /**
     * Перевірити, чи кімната ще не розпочата.
     */
    public function isWaiting(): bool
    {
        return $this->watch_party_status === WatchPartyStatus::WAITING;
    }

    /**
     * Розпочати перегляд у кімнаті.
     */
    public function start(): self
    {
        $this->update([
            'watch_party_status' => WatchPartyStatus::ACTIVE,
            'started_at' => now(),
        ]);

        return $this;
    }

    /**
     * Завершити перегляд у кімнаті.
     */
    public function end(): self
    {
        $this->update([
            'watch_party_status' => WatchPartyStatus::ENDED,
            'ended_at' => now(),
        ]);

        return $this;
    }

    /**
     * Отримати кількість активних глядачів.
     */
    public function getActiveViewersCount(): int
    {
        return $this->activeViewers()->count();
    }

    /**
     * Перевірити, чи кімната заповнена.
     */
    public function isFull(): bool
    {
        return $this->getActiveViewersCount() >= $this->max_viewers;
    }
}
