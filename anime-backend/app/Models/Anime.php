<?php

namespace AnimeSite\Models;

use AnimeSite\Enums\UserListType;
use AnimeSite\Models\Builders\AnimeQueryBuilder;
use AnimeSite\Models\Traits\HasSearchable;
use AnimeSite\Models\Traits\HasUserInteractions;
use AnimeSite\Notifications\NotifyAnnouncementToOngoing;
use AnimeSite\Notifications\NotifyNewSeasons;
use AnimeSite\Notifications\NotifyStatusChanges;
use Database\Factories\AnimeFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use AnimeSite\Casts\AnimeRelateCast;
use AnimeSite\Casts\ApiSourcesCast;
use AnimeSite\Casts\AttachmentsCast;
use AnimeSite\Enums\Country;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\Period;
use AnimeSite\Enums\RestrictedRating;
use AnimeSite\Enums\Source;
use AnimeSite\Enums\Status;
use AnimeSite\Enums\VideoQuality;
use AnimeSite\Models\Scopes\PublishedScope;
use AnimeSite\Models\Traits\HasFiles;
use AnimeSite\Models\Traits\HasSeo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @mixin IdeHelperAnime
 */
#[ScopedBy([PublishedScope::class])]
class Anime extends Model
{
    /** @use HasFactory<AnimeFactory> */
    use /*HasFactory,*/ HasSeo, HasSearchable, HasUlids, HasFiles, HasUserInteractions;

    protected $casts = [
        'aliases' => 'array',
        'countries' => 'array',
        'attachments' => 'array',
        'related' => 'array',
        'similars' => 'array',
        'imdb_score' => 'float',
        'first_air_date' => 'date',
        'last_air_date' => 'date',
        'api_sources' => 'array',
        'kind' => Kind::class,
        'status' => Status::class,
        'period' => Period::class,
        'restricted_rating' => RestrictedRating::class,
        'source' => Source::class,
    ];

    protected $hidden = ['searchable'];

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class)->chaperone();
    }

    /**
     * Зв'язок з тегами (поліморфний)
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'anime_person', 'anime_id', 'person_id')
            ->withPivot('character_name', 'voice_person_id')
            ->with('voiceActor');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class)->chaperone();
    }

    public function relatedQuery()
    {
        return Anime::whereIn('id', $this->related ?? []);
    }

    public function similarsQuery()
    {
        return Anime::whereIn('id', $this->similars ?? []);
    }


    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function selections(): MorphToMany
    {
        return $this->morphToMany(Selection::class, 'selectionable', 'selectionables');
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  Builder  $query
     * @return AnimeQueryBuilder
     */
    public function newEloquentBuilder($query): AnimeQueryBuilder
    {
        return new AnimeQueryBuilder($query);
    }

    /**
     * Get similar anime based on the 'similars' field
     *
     * @return Builder
     */
    public function getSimilarAnime(): Builder
    {
        // Якщо поле similars порожнє, повертаємо порожній запит
        if (empty($this->similars)) {
            return self::query()->whereRaw('1 = 0'); // Завжди повертає порожній результат
        }

        // Отримуємо аніме за ID зі списку similars
        return self::query()->whereIn('id', $this->similars);
    }

    /**
     * Get related anime based on the 'related' field
     *
     * @return Builder
     */
    public function getRelatedAnime(): Builder
    {
        // Якщо поле related порожнє, повертаємо порожній запит
        if (empty($this->related)) {
            return self::query()->whereRaw('1 = 0'); // Завжди повертає порожній результат
        }

        // Отримуємо ID аніме зі списку related
        $relatedIds = collect($this->related)->pluck('anime_id')->toArray();

        // Отримуємо аніме за ID
        return self::query()->whereIn('id', $relatedIds);
    }

    public function getRelatedAnimeWithType(): Collection
    {
        $relatedData = collect($this->related ?? []);

        if ($relatedData->isEmpty()) {
            return collect();
        }

        // Map: [anime_id => type] - fixed field name
        $map = $relatedData->pluck('type', 'id');

        // Get all corresponding animes
        $animes = self::query()
            ->whereIn('id', $map->keys())
            ->get();

        // Add type
        $animes->each(function ($anime) use ($map) {
            $anime->relation_type = $map[$anime->id] ?? null;
        });

        return $animes;
    }

    public function relatedAnimes()
    {
        $ids = $this->related?->pluck('anime_id')->filter()->unique();

        return Anime::whereIn('id', $ids)->get();
    }

    public function seasonsCount(): int
    {
        return $this->getRelatedAnimeWithType()
            ? $this->getRelatedAnimeWithType()->where('type', 'season')->count()
            : 0;
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
        return $this->hasManyThrough(
            WatchHistory::class,
            Episode::class,
            'anime_id',      // foreign key на episodes, який зв’язує з anime
            'episode_id',    // foreign key на watch_histories, який зв’язує з episodes
            'id',            // local key в anime
            'id'             // local key в episodes
        );
    }

    public function setPosterAttribute($value)
    {
        // Якщо $value вже повний URL — просто зберігаємо
        if (str_starts_with($value, 'https://')) {
            $this->attributes['poster'] = $value;
            return;
        }

        // Інакше формуємо повний URL за ключем файлу
        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');

        $this->attributes['poster'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
        $this->attributes['meta_image'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
    }
    public function setAttachmentsAttribute($value)
    {
        if (!is_array($value)) {
            return;
        }

        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');

        $formatted = collect($value)
            ->filter(fn ($item) => !empty($item['src']) && !empty($item['type']))
            ->map(function ($item) use ($storageAccount, $container) {
                $src = str_starts_with($item['src'], 'https://')
                    ? $item['src']
                    : "https://{$storageAccount}.blob.core.windows.net/{$container}/{$item['src']}";

                return [
                    'type' => $item['type'],
                    'src' => $src,
                ];
            })
            ->values()
            ->toArray();

        $this->attributes['attachments'] = json_encode($formatted);
    }

    protected static function booted()
    {
        static::created(function (Anime $newAnime) {
            // Витягуємо лише ID пов'язаних аніме
            $relatedAnimeIds = collect($newAnime->related)
                ->pluck('anime_id')
                ->filter()
                ->unique()
                ->toArray();

            // Завантажуємо пов’язані аніме з БД
            $relatedAnimes = Anime::whereIn('id', $relatedAnimeIds)->get();

            foreach ($relatedAnimes as $relatedAnime) {
                $userIds = DB::table('user_lists')
                    ->where('listable_type', Anime::class)
                    ->where('listable_id', $relatedAnime->id)
                    ->whereIn('type', [
                        UserListType::FAVORITE->value,
                        UserListType::WATCHING->value,
                        UserListType::PLANNED->value,
                    ])
                    ->pluck('user_id');

                // Завантажуємо тільки тих користувачів, у кого notify_new_seasons увімкнено
                User::whereIn('id', $userIds)
                    ->where('notify_new_seasons', true)
                    ->get()
                    ->each(function (User $user) use ($newAnime, $relatedAnime) {
                        $user->notify(new NotifyNewSeasons($newAnime, $relatedAnime));
                    });
            }
        });
        static::updated(function (Anime $anime) {
            // Перевіряємо, чи статус змінився
            if (! $anime->isDirty('status')) {
                return;
            }

            // Перевіряємо нове значення статусу
            $newStatus = $anime->status;

            // Якщо статус не ONGOING — надсилаємо загальну нотифікацію
            if ($newStatus !== Status::ONGOING) {
                $userIds = DB::table('user_lists')
                    ->where('listable_type', Anime::class)
                    ->where('listable_id', $anime->id)
                    ->whereIn('type', [
                        UserListType::FAVORITE->value,
                        UserListType::WATCHING->value,
                        UserListType::PLANNED->value,
                    ])
                    ->pluck('user_id');

                User::whereIn('id', $userIds)
                    ->where('notify_status_changes', true)
                    ->get()
                    ->each(fn (User $user) => $user->notify(new NotifyStatusChanges($anime)));
            }

            // Якщо статус саме ONGOING — надсилаємо лише специфічну нотифікацію
            if ($newStatus === Status::ONGOING) {
                $userIds = DB::table('user_lists')
                    ->where('listable_type', Anime::class)
                    ->where('listable_id', $anime->id)
                    ->whereIn('type', [
                        UserListType::FAVORITE->value,
                        UserListType::WATCHING->value,
                        UserListType::PLANNED->value,
                    ])
                    ->pluck('user_id');

                User::whereIn('id', $userIds)
                    ->where('notify_announcement_to_ongoing', true)
                    ->get()
                    ->each(fn (User $user) => $user->notify(new NotifyAnnouncementToOngoing($anime)));
            }
        });
    }

}
