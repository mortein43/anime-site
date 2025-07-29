<?php

namespace AnimeSite\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        $histories = $user->watchHistories()->get(['progress_time', 'created_at']);

        $totalSeconds = $histories->sum('progress_time');
        $totalHours = floor($totalSeconds / 3600);
        $totalDays = floor($totalHours / 24);
        $totalMonths = floor($totalDays / 30);

        $hoursByMonth = $histories
            ->groupBy(fn ($item) => $item->created_at->format('Y-m'))
            ->sortKeysDesc() // сортуємо за спаданням (останній місяць перший)
            ->take(12)        // беремо лише останні 12 місяців
            ->reverse()       // щоб від найстарішого до найновішого
            ->map(fn ($group) => floor($group->sum('progress_time') / 3600));

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role?->value,
            'gender' => $this->gender?->value,
            'avatar' => $this->avatar,
            'backdrop' => $this->backdrop,
            'description' => strip_tags($this->description),
            'birthday' => $this->birthday?->format('Y-m-d'),
            'allow_adult' => $this->allow_adult,
            'is_auto_next' => $this->is_auto_next,
            'is_auto_play' => $this->is_auto_play,
            'is_auto_skip_intro' => $this->is_auto_skip_intro,
            'is_private_favorites' => $this->is_private_favorites,
            'is_banned' => $this->is_banned,
            'new_episodes' => $this->notify_new_episodes,
            'episode_date_changes' => $this->notify_episode_date_changes,
            'announcement_to_ongoing' => $this->notify_announcement_to_ongoing,
            'comment_replies' => $this->notify_comment_replies,
            'comment_likes' => $this->notify_comment_likes,
            'review_replies' => $this->notify_review_replies,
            'planned_reminders' => $this->notify_planned_reminders,
            'new_selections' => $this->notify_new_selections,
            'status_changes' => $this->notify_status_changes,
            'new_seasons' => $this->notify_new_seasons,
            'subscription_expiration' => $this->notify_subscription_expiration,
            'subscription_renewal' => $this->notify_subscription_renewal,
            'payment_issues' => $this->notify_payment_issues,
            'tariff_changes' => $this->notify_tariff_changes,
            'site_updates' => $this->notify_site_updates,
            'maintenance' => $this->notify_maintenance,
            'security_changes' => $this->notify_security_changes,
            'new_features' => $this->notify_new_features,
            'email_verified_at' => $this->email_verified_at,
            'last_seen_at' => $this->last_seen_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'list_counts' => [
                'watching' => $this->watching_animes_count ?? 0,
                'planned' => $this->planned_animes_count ?? 0,
                'watched' => $this->watched_animes_count ?? 0,
                'stopped' => $this->stopped_animes_count ?? 0,
                'rewatching' => $this->re_watching_animes_count ?? 0,
            ],
            'last_watched_episodes' => $this->whenLoaded('watchHistories', function () {
                return $this->watchHistories->map(function ($history) {
                    return [
                        'id' => $history->id,
                        'progress_time' => $history->progress_time,
                        'watched_at' => $history->created_at->toDateTimeString(),
                        'episode' => [
                            'id' => $history->episode->id,
                            'name' => $history->episode->name,
                            'number' => $history->episode->number,
                            'air_date' => optional($history->episode->air_date)->format('Y-m-d'),
                            'anime' => [
                                'id' => $history->episode->anime->id,
                                'name' => $history->episode->anime->name,
                                'poster' => $history->episode->anime->poster,
                            ],
                        ],
                    ];
                });
            }),
            'favorite_animes' => $this->whenLoaded('favoriteAnimesPreview', function () {
                return $this->favoriteAnimesPreview->map(function ($item) {
                    if (!$item->relationLoaded('listable')) {
                        return null;
                    }

                    $anime = $item->listable;

                    return [
                        'id' => $anime->id,
                        'title' => $anime->name,
                        'poster' => $anime->poster?? null,
                        'year' => optional($anime->first_air_date)->format('Y'),
                        'kind' => $anime->kind, // Remove this if you don't need it, or set to a safe value
                    ];
                })->filter();
            }, []),

            'favorite_people' => $this->whenLoaded('favoritePeoplePreview', function () {
                return $this->favoritePeoplePreview->map(function ($item) {
                    if (!$item->relationLoaded('listable')) {
                        return null;
                    }

                    $person = $item->listable;
                    return [
                        'id' => $person->id,
                        'name' => $person->name,
                        'poster' => $person->image ?? null,
                        'year' => optional($person->birthday)->format('Y'),
                        'type' => null, // Remove this if you don't need it
                    ];
                })->filter();
            }, []),

            // Інші лічильники
            'ratings_count' => $this->ratings_count ?? 0,
            'comments_count' => $this->comments_count ?? 0,
            'subscriptions_count' => $this->subscriptions_count ?? 0,
            'achievements_count' => $this->achievements_count ?? 0,
            'watch_time' => [
                'total_hours' => $totalHours,
                'total_days' => $totalDays,
                'total_months' => $totalMonths,
                'hours_by_month' => $hoursByMonth,
            ],
        ];
        if ($this->birthday) {
            $data['age'] = $this->age;
        }

        if ($this->last_seen_at) {
            $data['is_online'] = $this->isOnline;
            $data['formatted_last_seen'] = $this->formattedLastSeen;
        }

        return $data;
    }
}
