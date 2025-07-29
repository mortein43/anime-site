<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use AnimeSite\Enums\Country;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\LanguageCode;
use AnimeSite\Enums\VideoPlayerName;
use AnimeSite\Enums\VideoQuality;
use AnimeSite\Models\Episode;
use AnimeSite\Models\Anime;
use AnimeSite\ValueObjects\VideoPlayer;

/**
 * @extends Factory<Episode>
 */
class EpisodeFactory extends Factory
{
    public function definition(): array
    {
        $anime = Anime::query()->inRandomOrder()->first();
        $isanimeKind = $anime->kind === Kind::TV_SERIES; // Перевірка типу Anime

        $name = $this->faker->sentence(3);

        return [
            'anime_id' => $anime->id,
            'number' => $this->generateUniqueNumber($anime->id, $isanimeKind),
            'slug' => $name,
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->numberBetween(20, 120), // Duration in minutes
            'air_date' => $this->faker->optional()->dateTimeBetween('-2 years', 'now'),
            'is_filler' => $this->faker->boolean(10), // 10% chance of being filler
            'pictures' => json_encode($this->generatePictureUrls(rand(1, 3))),
            'video_players' => $this->generateVideoPlayers(),
            'meta_title' => $this->faker->optional()->sentence(5),
            'meta_description' => $this->faker->optional()->sentence(10),
            'meta_image' => $this->faker->optional()->imageUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function generateUniqueNumber(string $animeId, bool $isAnimeKind): int
    {
        if ($isAnimeKind) {
            return 1; // Для фільмів завжди номер 1
        }

        // Отримуємо всі існуючі номери для конкретного фільму
        $existingNumbers = collect(Episode::where('anime_id', $animeId)->pluck('number'));

        // Знаходимо наступний доступний номер
        $newNumber = 1;
        while ($existingNumbers->contains($newNumber)) {
            $newNumber++;
        }

        return $newNumber;
    }

    private function generatePictureUrls(int $count): array
    {
        return $this->faker->randomElements([
            $this->faker->imageUrl(640, 480, 'anime', true, 'Episode 1'),
            $this->faker->imageUrl(640, 480, 'anime', true, 'Episode 2'),
            $this->faker->imageUrl(640, 480, 'anime', true, 'Episode 3'),
        ], $count);
    }

    /**
     * @return Collection<VideoPlayer>
     */
    private function generateVideoPlayers(): Collection
    {
        $videoPlayers = collect([
            [

                'file_url' => $this->faker->url(),
                'voiceover_team_id' => \AnimeSite\Models\VoiceoverTeam::inRandomOrder()->value('name'),
            ],
            [

                'file_url' => $this->faker->url(),
                'voiceover_team_id' => \AnimeSite\Models\VoiceoverTeam::inRandomOrder()->value('name'),
            ],
        ]);

        return $videoPlayers->map(fn ($data) => new VideoPlayer(
            $data['file_url'],
            $data['voiceover_team_id'],
        ));
    }

    public function forAnime(Anime $anime): self
    {
        return $this->state(fn () => [
            'anime_id' => $anime->id,
        ]);
    }
}
