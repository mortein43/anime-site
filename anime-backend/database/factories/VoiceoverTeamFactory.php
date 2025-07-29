<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use AnimeSite\Models\VoiceoverTeam;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AnimeSite\Models\VoiceoverTeam>
 */
class VoiceoverTeamFactory extends Factory
{
    protected $model = VoiceoverTeam::class;

    public function definition()
    {
        $company = $this->faker->unique()->company();
        /*        $slug = Str::slug($company);

                // Ensure unique slug by checking if it already exists
                $slug = $this->ensureUniqueSlug($slug);
        */

        // return [
        //     'slug' => $company,
        //     'name' => $company,
        //     'description' => $this->faker->paragraph(),
        //     'image' => $this->faker->imageUrl(),
        //     'meta_title' => $this->faker->sentence(),
        //     'meta_description' => $this->faker->sentence(),
        //     'meta_image' => $this->faker->imageUrl(),
        // ];
        $slug = Str::slug($company . '-' . Str::random(5));
        return [
            'slug' => $slug,
        'name' => $company,
        'description' => $this->faker->paragraph(),
        'image' => $this->faker->imageUrl(),
        'meta_title' => $this->faker->sentence(),
        'meta_description' => $this->faker->sentence(),
        'meta_image' => $this->faker->imageUrl(),
        ];
    }
}
