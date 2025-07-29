<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Illuminate\Database\Eloquent\Model>
 */
class AnimePersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Список популярних імен персонажів аніме
        $characterNames = [
            // Naruto characters
            'Naruto Uzumaki', 'Sasuke Uchiha', 'Sakura Haruno', 'Kakashi Hatake', 'Hinata Hyuga',
            'Shikamaru Nara', 'Ino Yamanaka', 'Choji Akimichi', 'Rock Lee', 'Neji Hyuga',
            'Tenten', 'Gaara', 'Temari', 'Kankuro', 'Orochimaru', 'Jiraiya', 'Tsunade',
            
            // Generic anime character names
            'Akira Yamamoto', 'Yuki Tanaka', 'Hiroshi Sato', 'Mei Watanabe', 'Takeshi Nakamura',
            'Rin Kobayashi', 'Daichi Suzuki', 'Hana Kimura', 'Kenji Ito', 'Ayame Yoshida',
            'Ryo Hayashi', 'Nana Fujiwara', 'Sota Matsumoto', 'Yui Inoue', 'Kaito Takahashi',
            'Miku Yamada', 'Haruto Sasaki', 'Emi Yamazaki', 'Ren Mori', 'Saki Kato',
            
            // Fantasy/Action character names
            'Kage no Senshi', 'Hikari no Miko', 'Tsuki no Kishi', 'Honoo no Majutsushi',
            'Kaze no Tsukai', 'Mizu no Megami', 'Daichi no Ou', 'Raiden Shogun',
            'Yami no Shinobi', 'Gin no Kenshi', 'Kurenai no Majo', 'Ao no Exorcist',
        ];

        return [
            'anime_id' => Anime::factory(),
            'person_id' => Person::factory(),
            'voice_person_id' => $this->faker->boolean(30) ? Person::factory() : null, // 30% шанс мати актора озвучення
            'character_name' => $this->faker->randomElement($characterNames),
        ];
    }

    /**
     * Indicate that the anime person is for a specific anime.
     */
    public function forAnime(Anime $anime): static
    {
        return $this->state(fn (array $attributes) => [
            'anime_id' => $anime->id,
        ]);
    }

    /**
     * Indicate that the anime person is for a specific person.
     */
    public function forPerson(Person $person): static
    {
        return $this->state(fn (array $attributes) => [
            'person_id' => $person->id,
        ]);
    }

    /**
     * Indicate that the anime person has a voice actor.
     */
    public function withVoiceActor(Person $voiceActor = null): static
    {
        return $this->state(fn (array $attributes) => [
            'voice_person_id' => $voiceActor?->id ?? Person::factory(),
        ]);
    }

    /**
     * Indicate that the anime person has no voice actor.
     */
    public function withoutVoiceActor(): static
    {
        return $this->state(fn (array $attributes) => [
            'voice_person_id' => null,
        ]);
    }

    /**
     * Generate Naruto-specific character names.
     */
    public function narutoCharacter(): static
    {
        $narutoCharacters = [
            'Naruto Uzumaki', 'Sasuke Uchiha', 'Sakura Haruno', 'Kakashi Hatake',
            'Hinata Hyuga', 'Shikamaru Nara', 'Ino Yamanaka', 'Choji Akimichi',
            'Rock Lee', 'Neji Hyuga', 'Tenten', 'Gaara', 'Temari', 'Kankuro',
            'Iruka Umino', 'Hiruzen Sarutobi', 'Orochimaru', 'Jiraiya', 'Tsunade',
            'Itachi Uchiha', 'Kisame Hoshigaki', 'Deidara', 'Sasori', 'Hidan',
            'Kakuzu', 'Tobi', 'Pain', 'Konan', 'Zetsu', 'Madara Uchiha'
        ];

        return $this->state(fn (array $attributes) => [
            'character_name' => $this->faker->randomElement($narutoCharacters),
        ]);
    }

    /**
     * Generate main character names (protagonists).
     */
    public function mainCharacter(): static
    {
        $mainCharacters = [
            'Naruto Uzumaki', 'Sasuke Uchiha', 'Sakura Haruno', 'Kakashi Hatake',
            'Hinata Hyuga', 'Gaara', 'Rock Lee', 'Shikamaru Nara'
        ];

        return $this->state(fn (array $attributes) => [
            'character_name' => $this->faker->randomElement($mainCharacters),
        ]);
    }
}
