<?php

namespace Database\Factories;

use AnimeSite\Enums\WatchPartyStatus;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Episode;
use AnimeSite\Models\User;
use AnimeSite\Models\WatchParty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AnimeSite\Models\WatchParty>
 */
class WatchPartyFactory extends Factory
{
    protected $model = WatchParty::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        $isPrivate = $this->faker->boolean(20); // 20% шанс, що кімната приватна

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(6),
            'user_id' => User::factory(),
            'episode_id' => Episode::factory(),
            'is_private' => $isPrivate,
            'watch_party_status' => WatchPartyStatus::WAITING,
            'password' => $isPrivate ? bcrypt('password') : null,
            'max_viewers' => $this->faker->numberBetween(5, 20),
            'started_at' => null,
            'ended_at' => null,
        ];
    }

    /**
     * Вказати, що кімната активна.
     */
    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'watch_party_status' => WatchPartyStatus::ACTIVE,
                'started_at' => now()->subMinutes($this->faker->numberBetween(5, 120)),
                'ended_at' => null,
            ];
        });
    }

    /**
     * Вказати, що кімната завершена.
     */
    public function ended(): self
    {
        return $this->state(function (array $attributes) {
            $startedAt = now()->subHours($this->faker->numberBetween(1, 24));

            return [
                'watch_party_status' => WatchPartyStatus::ENDED,
                'started_at' => $startedAt,
                'ended_at' => $startedAt->copy()->addMinutes($this->faker->numberBetween(30, 180)),
            ];
        });
    }

    /**
     * Вказати, що кімната ще не розпочата.
     */
    public function waiting(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'watch_party_status' => WatchPartyStatus::WAITING,
                'started_at' => null,
                'ended_at' => null,
            ];
        });
    }

    /**
     * Вказати, що кімната публічна.
     */
    public function public(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_private' => false,
                'password' => null,
            ];
        });
    }

    /**
     * Вказати, що кімната приватна.
     */
    public function private(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_private' => true,
                'password' => bcrypt('password'),
            ];
        });
    }
}
