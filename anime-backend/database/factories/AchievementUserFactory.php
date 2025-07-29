<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use AnimeSite\Models\Achievement;
use AnimeSite\Models\AchievementUser;
use AnimeSite\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AnimeSite\Models\AchievementUser>
 */
class AchievementUserFactory extends Factory
{
    protected $model = AchievementUser::class;

    public function definition()
    {
        // return [
        //     'id' => Str::ulid(),
        //     'user_id' => User::factory(),
        //     'achievement_id' => Achievement::factory(),
        //     'progress_count' => $this->faker->numberBetween(0, 100),
        // ];
        return [
            'id' => Str::ulid(),

            // Вибрати випадкового користувача або створити нового з унікальними даними
            'user_id' => function () {
                return User::inRandomOrder()->first()?->id
                    ?? User::factory()->create([
                        'name' => $this->faker->unique()->userName,
                        'email' => $this->faker->unique()->safeEmail,
                    ])->id;
            },

            // Вибрати випадкове досягнення або створити нове
            'achievement_id' => function () {
                return Achievement::inRandomOrder()->first()?->id
                    ?? Achievement::factory()->create()->id;
            },

            'progress_count' => $this->faker->numberBetween(0, 100),
        ];
    }
}
