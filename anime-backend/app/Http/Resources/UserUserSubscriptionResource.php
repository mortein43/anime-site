<?php

namespace AnimeSite\Http\Resources;

use AnimeSite\Http\Resources\TariffResource;
use AnimeSite\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for user subscriptions in user context (without user relation)
 *
 * @mixin UserSubscription
 */
class UserUserSubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tariff_id' => $this->tariff_id,
            'start_date' => $this->start_date->format('Y-m-d H:i:s'),
            'end_date' => $this->end_date->format('Y-m-d H:i:s'),
            'is_active' => $this->is_active,
            'auto_renew' => $this->auto_renew,
            'days_left' => $this->daysLeft(),
            'tariff' => new TariffResource($this->whenLoaded('tariff')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
