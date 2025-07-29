<?php

namespace AnimeSite\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use AnimeSite\Builders\UserBuilder;
use AnimeSite\Models\Builders\UserQueryBuilder;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use AnimeSite\Enums\Gender;
use AnimeSite\Enums\NotificationType;
use AnimeSite\Enums\Role;
use AnimeSite\Enums\UserListType;
use AnimeSite\Models\Traits\HasFiles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory,  HasUlids, Notifiable, HasFiles, HasApiTokens;
    protected $casts = [
        'role' => Role::class,
        'gender' => Gender::class,
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'password' => 'hashed',
        'last_seen_at' => 'datetime',

        // Ban status
        'is_banned' => 'boolean',

        // User preferences
        'allow_adult' => 'boolean',
        'is_auto_next' => 'boolean',
        'is_auto_play' => 'boolean',
        'is_auto_skip_intro' => 'boolean',
        'is_private_favorites' => 'boolean',

        // Notification preferences - Episodes
        'notify_new_episodes' => 'boolean',
        'notify_episode_date_changes' => 'boolean',


        // Notification preferences - Comments
        'notify_comment_replies' => 'boolean',
        'notify_comment_likes' => 'boolean',

        // Notification preferences - Ratings
        'notify_review_replies' => 'boolean',

        // Notification preferences - UserList
        'notify_planned_reminders' => 'boolean',

        // Notification preferences - Selections
        'notify_new_selections' => 'boolean',

        // Notification preferences - Movies
        'notify_status_changes' => 'boolean',
        'notify_new_seasons' => 'boolean',
        'notify_announcement_to_ongoing' => 'boolean',

        // Notification preferences - Subscription
        'notify_subscription_expiration' => 'boolean',
        'notify_subscription_renewal' => 'boolean',
        'notify_payment_issues' => 'boolean',
        'notify_tariff_changes' => 'boolean',

        // Notification preferences - System
        'notify_site_updates' => 'boolean',
        'notify_maintenance' => 'boolean',
        'notify_security_changes' => 'boolean',
        'notify_new_features' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class)->chaperone();
    }

    public function animeNotifications()
    {
        return $this->belongsToMany(Anime::class, 'anime_user_notifications')
            ->as('notification')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->chaperone();
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class)->chaperone();
    }

    public function commentReports(): HasMany
    {
        return $this->hasMany(CommentReport::class)->chaperone();
    }

    public function searchHistories(): HasMany
    {
        return $this->hasMany(SearchHistory::class)->chaperone();
    }

    public function watchHistories()
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function watchedEpisodes()
    {
        return $this->belongsToMany(Episode::class, 'watch_histories')
            ->withPivot('progress_time', 'created_at', 'updated_at')
            ->withTimestamps();
    }
    public function selections(): HasMany
    {
        return $this->HasMany(Selection::class)->chaperone();
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'achievement_user')
            ->withPivot('progress_count');
    }
    public function hostedWatchParties(): HasMany
    {
        return $this->hasMany(WatchParty::class, 'host_user_id');
    }

    public function watchPartyMessages(): HasMany
    {
        return $this->hasMany(WatchPartyMessage::class);
    }

    public function participatedWatchParties(): BelongsToMany
    {
        return $this->belongsToMany(WatchParty::class, 'watch_party_participants');
    }

    // Helper methods
    public function joinWatchParty(WatchParty $watchParty): bool
    {
        if (!$watchParty->isParticipant($this->id)) {
            $watchParty->addParticipant($this->id);
            return true;
        }
        return false;
    }

    public function leaveWatchParty(WatchParty $watchParty): bool
    {
        if ($watchParty->isParticipant($this->id) && !$watchParty->isHost($this->id)) {
            $watchParty->removeParticipant($this->id);
            return true;
        }
        return false;
    }

    public function sendMessageToWatchParty(WatchParty $watchParty, string $message): ?WatchPartyMessage
    {
        if ($watchParty->isParticipant($this->id)) {
            return WatchPartyMessage::create([
                'watch_party_id' => $watchParty->id,
                'user_id' => $this->id,
                'message' => $message,
            ]);
        }
        return null;
    }

    public function achievementsPivot()
    {
        return $this->hasMany(AchievementUser::class, 'user_id');
    }

    public function favoriteAnimes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Anime::class)
            ->where('type', UserListType::FAVORITE->value);
    }
    public function favoriteSelections(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Selection::class)
            ->where('type', UserListType::FAVORITE->value);
    }
    public function favoritePeople(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Person::class)
            ->where('type', UserListType::FAVORITE->value);
    }

    public function favoriteTags(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Tag::class)
            ->where('type', UserListType::FAVORITE->value);
    }

    public function favoriteEpisodes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Person::class)
            ->where('type', UserListType::FAVORITE->value);
    }

    public function favoriteAnimesPreview(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Anime::class)
            ->where('type', UserListType::FAVORITE->value)
            ->with('listable') // зв’язок із Anime
            ->latest()
            ->limit(5);
    }

    public function favoritePeoplePreview(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Person::class)
            ->where('type', UserListType::FAVORITE->value)
            ->with('listable') // зв’язок із Person
            ->latest()
            ->limit(5);
    }

    public function getAgeAttribute()
    {
        if (!$this->birthday) {
            return null;
        }

        return Carbon::parse($this->birthday)->age;
    }
    public function getIsOnlineAttribute(): bool
    {
        if (!$this->last_seen_at) {
            return false;
        }

        // Тепер last_seen_at - це Carbon, можна використовувати gt()
        return $this->last_seen_at->gt(now()->subMinutes(5));
    }

    public function getFormattedLastSeenAttribute(): ?string
    {
        if (!$this->last_seen_at) {
            return null;
        }

        return $this->last_seen_at->diffForHumans();
    }

    public function userLists(): HasMany
    {
        return $this->hasMany(UserList::class);
    }



    public function watchingAnimes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Anime::class)
            ->where('type', UserListType::WATCHING->value);
    }

    public function plannedAnimes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Anime::class)
            ->where('type', UserListType::PLANNED->value);
    }

    public function watchedAnimes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Anime::class)
            ->where('type', UserListType::WATCHED->value);
    }

    public function stoppedAnimes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Anime::class)
            ->where('type', UserListType::STOPPED->value);
    }

    public function reWatchingAnimes(): HasMany
    {
        return $this->userLists()
            ->where('listable_type', Anime::class)
            ->where('type', UserListType::REWATCHING->value);
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        return $this->role == Role::ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role == Role::MODERATOR;
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return UserQueryBuilder
     */
    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    public function subscriptions() : HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }
    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function setAvatarAttribute($value)
    {
        // Якщо $value вже повний URL — просто зберігаємо
        if (str_starts_with($value, 'https://')) {
            $this->attributes['avatar'] = $value;
            return;
        }

        // Інакше формуємо повний URL за ключем файлу
        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');
        $value = ltrim(preg_replace('#^images/#', '', $value), '/');
        $this->attributes['avatar'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
    }
    public function setBackdropAttribute($value)
    {
        // Якщо $value вже повний URL — просто зберігаємо
        if (str_starts_with($value, 'https://')) {
            $this->attributes['backdrop'] = $value;
            return;
        }

        // Інакше формуємо повний URL за ключем файлу
        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');
        $value = ltrim(preg_replace('#^images/#', '', $value), '/');
        $this->attributes['backdrop'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
    }

}
