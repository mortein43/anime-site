<?php

namespace AnimeSite\Models;

use AnimeSite\Builders\EpisodeBuilder;
use AnimeSite\Enums\UserListType;
use AnimeSite\Models\Builders\EpisodeQueryBuilder;
use AnimeSite\Models\Traits\HasUserInteractions;
use AnimeSite\Notifications\NotifyEpisodeDateChanges;
use AnimeSite\Notifications\NotifyNewEpisodes;
use Database\Factories\EpisodeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use AnimeSite\Casts\VideoPlayersCast;
use AnimeSite\Models\Traits\HasFiles;
use AnimeSite\Models\Traits\HasSeo;
use AnimeSite\ValueObjects\VideoPlayer;

/**
 * @mixin IdeHelperEpisode
 */
class Episode extends Model
{
    /** @use HasFactory<EpisodeFactory> */
    use /*HasFactory,*/  HasSeo, HasUlids, HasFiles, HasUserInteractions;

    protected $casts = [
        'pictures' => 'array',
        'video_players' => 'array',
        'air_date' => 'date',
    ];



    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function selections(): MorphToMany
    {
        return $this->morphToMany(Selection::class, 'selectionable', 'selectionables');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function watchParties(): HasMany
    {
        return $this->hasMany(WatchParty::class);
    }

    public function activeWatchParties(): HasMany
    {
        return $this->hasMany(WatchParty::class)->active();
    }

    private function formatDuration(int $duration): string
    {
        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        $formatted = [];

        if ($hours > 0) {
            $formatted[] = "{$hours} год";
        }

        if ($minutes > 0) {
            $formatted[] = "{$minutes} хв";
        }

        return implode(' ', $formatted);
    }
    /**
     * Get the primary picture URL attribute.
     *
     * @return Attribute
     */
    protected function pictureUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->pictures)) {
                    return null;
                }

                $pictures = is_string($this->pictures) ? json_decode($this->pictures, true) : $this->pictures;

                if (empty($pictures)) {
                    return null;
                }

                $firstPicture = is_array($pictures) ? reset($pictures) : $pictures;

                return is_string($firstPicture) ? $this->getFileUrl($firstPicture) : null;
            }
        );
    }

    /**
     * Get all picture URLs attribute.
     *
     * @return Attribute
     */
    protected function picturesUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->pictures)) {
                    return [];
                }

                $pictures = is_string($this->pictures) ? json_decode($this->pictures, true) : $this->pictures;

                if (empty($pictures)) {
                    return [];
                }

                return collect($pictures)->map(function ($picture) {
                    return is_string($picture) ? $this->getFileUrl($picture) : null;
                })->filter()->values()->toArray();
            }
        );
    }

    /**
     * Get the meta image URL attribute.
     *
     * @return Attribute
     */
    protected function metaImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFileUrl($this->meta_image)
        );
    }

    /**
     * Get the formatted duration attribute.
     *
     * @return Attribute
     */
    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->duration ? $this->formatDuration($this->duration) : null
        );
    }

    /**
     * Get the full name attribute (with episode number).
     *
     * @return Attribute
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "Episode {$this->number}: {$this->name}"
        );
    }

    /**
     * Get the comments count attribute.
     *
     * @return Attribute
     */
    protected function commentsCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->comments()->count()
        );
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \AnimeSite\Models\Builders\EpisodeQueryBuilder
     */
    public function newEloquentBuilder($query): EpisodeQueryBuilder
    {
        return new EpisodeQueryBuilder($query);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function watchHistories()
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function watchedByUsers()
    {
        return $this->belongsToMany(User::class, 'watch_histories')
            ->withPivot('progress_time', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    public function setPicturesAttribute($value)
    {
        // Якщо значення не масив — нічого не робимо
        if (!is_array($value)) {
            return;
        }

        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');

        $fullUrls = collect($value)
            ->filter() // Прибираємо null / порожні
            ->map(function ($item) use ($storageAccount, $container) {
                return str_starts_with($item, 'https://')
                    ? $item
                    : "https://{$storageAccount}.blob.core.windows.net/{$container}/{$item}";
            })
            ->values()
            ->toArray();

        $this->attributes['pictures'] = json_encode($fullUrls);
        if (!empty($fullUrls)) {
            $this->attributes['meta_image'] = $fullUrls[0];
        }
    }
    public function setFileUrlAttribute($value)
{

    // Якщо це відносний шлях — формуємо повний URL до Azure
    $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
    $container = env('AZURE_STORAGE_CONTAINER', 'videos'); // Змінив на videos (як у вашому disk)

    $this->attributes['file_url'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
}
    // public function setVideoPlayersAttribute($value)
    // {
    //     $this->attributes['video_players'] = json_encode(array_map(function($item) {
    //         if (isset($item['file_url']) && !empty($item['file_url'])) {
    //             // Якщо це не URL, перетворюємо на повний шлях
    //             if (!str_starts_with($item['file_url'], 'http')) {
    //                 $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
    //                 $container = env('AZURE_STORAGE_CONTAINER', 'videos');
    //                 $item['file_url'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$item['file_url']}";
    //             }
    //         }
    //         return $item;
    //     }, $value));
    // }
    public function setVideoPlayersAttribute($value)
{
    if (!is_array($value)) {
        $value = json_decode($value, true);
        if (!is_array($value)) {
            $value = [];
        }
    }

    $this->attributes['video_players'] = json_encode(array_map(function ($item) {
        if (isset($item['file_url']) && !empty($item['file_url'])) {
            if (!str_starts_with($item['file_url'], 'http')) {
                $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
                $container = env('AZURE_STORAGE_CONTAINER', 'images');
                $item['file_url'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$item['file_url']}";
            }
        }
        return $item;
    }, $value));
}


    protected static function booted()
    {
        static::created(function (Episode $episode) {
            // Твоя логіка при створенні епізоду
            // Наприклад, відправити нотифікації користувачам
            $anime = $episode->anime;

            $userIds = UserList::where('listable_type', Anime::class)
                ->where('listable_id', $anime->id)
                ->whereIn('type', [
                    UserListType::FAVORITE->value,
                    UserListType::WATCHING->value,
                    UserListType::PLANNED->value,
                ])
                ->pluck('user_id');

            User::whereIn('id', $userIds)
                ->get()
                ->where('notify_new_episodes', true)
                ->each(function ($user) use ($episode, $anime) {
                    $user->notify(new NotifyNewEpisodes($episode, $anime));
                });
        });

        static::updating(function (Episode $episode) {
            if ($episode->isDirty('air_date')) {
                $oldDate = $episode->getOriginal('air_date');
                $anime = $episode->anime;

                $userIds = UserList::where('listable_type', Anime::class)
                    ->where('listable_id', $anime->id)
                    ->whereIn('type', ['favorite', 'watching', 'planned'])
                    ->pluck('user_id');

                User::whereIn('id', $userIds)
                    ->where('notify_episode_date_changes', true)
                    ->get()
                    ->each(function ($user) use ($episode, $anime, $oldDate) {
                        $user->notify(new NotifyEpisodeDateChanges($episode, $anime, $oldDate));
                    });
            }
        });
    }
}
