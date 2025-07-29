<?php

namespace AnimeSite\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
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

            // Нотифікації
            'notify' => [
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
            ],
        ];
    }
}
