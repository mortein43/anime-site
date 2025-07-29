<?php

namespace Database\Factories;

use AnimeSite\Enums\WatchPartyStatus;
use AnimeSite\Models\User;
use AnimeSite\Models\WatchParty;
use AnimeSite\Models\WatchPartyMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AnimeSite\Models\WatchPartyMessage>
 */
class WatchPartyMessageFactory extends Factory
{
    protected $model = WatchPartyMessage::class;

    public function definition(): array
    {
        // Select an existing ACTIVE or ENDED WatchParty
        $party = WatchParty::whereIn('watch_party_status', [WatchPartyStatus::ACTIVE, WatchPartyStatus::ENDED])
            ->inRandomOrder()
            ->first();

        if (!$party) {
            throw new \Exception('No ACTIVE or ENDED WatchParty records exist. Please create WatchParty records before using this factory.');
        }

        // Get possible senders (host or viewers from watch_party_user)
        $viewers = $party->viewers()->pluck('users.id')->toArray();
        $possibleSenders = array_merge([$party->user_id], $viewers);
        $userId = !empty($possibleSenders) ? $possibleSenders[array_rand($possibleSenders)] : null;

        if (!$userId) {
            throw new \Exception('No valid users found for WatchParty ID: ' . $party->id);
        }

        // Get joined_at for the user if they are a viewer, otherwise use started_at
        $joinedAt = $party->viewers()->where('user_id', $userId)->first()?->pivot->joined_at ?? $party->started_at;

        // Ensure joinedAt is not null (shouldn't be for ACTIVE/ENDED parties)
        if (!$joinedAt) {
            $joinedAt = now()->subMinutes(rand(5, 120)); // Fallback to a reasonable time
        }

        return [
            'id' => Str::ulid(),
            'watch_party_id' => $party->id,
            'user_id' => $userId,
            'message' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
