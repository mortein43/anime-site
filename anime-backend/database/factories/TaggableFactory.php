<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use AnimeSite\Models\Tag;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Person;
use AnimeSite\Models\Selection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Illuminate\Database\Eloquent\Model>
 */
class TaggableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Випадково вибираємо тип моделі для тегування
        $taggableTypes = [
            Anime::class,
            Person::class,
            Selection::class,
        ];
        
        $taggableType = $this->faker->randomElement($taggableTypes);
        
        // Отримуємо випадковий об'єкт відповідного типу
        $taggableModel = $taggableType::factory();
        
        return [
            'tag_id' => Tag::factory(),
            'taggable_id' => $taggableModel,
            'taggable_type' => $taggableType,
        ];
    }

    /**
     * Indicate that the taggable is for a specific tag.
     */
    public function forTag(Tag $tag): static
    {
        return $this->state(fn (array $attributes) => [
            'tag_id' => $tag->id,
        ]);
    }

    /**
     * Indicate that the taggable is for a specific anime.
     */
    public function forAnime(Anime $anime): static
    {
        return $this->state(fn (array $attributes) => [
            'taggable_id' => $anime->id,
            'taggable_type' => Anime::class,
        ]);
    }

    /**
     * Indicate that the taggable is for a specific person.
     */
    public function forPerson(Person $person): static
    {
        return $this->state(fn (array $attributes) => [
            'taggable_id' => $person->id,
            'taggable_type' => Person::class,
        ]);
    }

    /**
     * Indicate that the taggable is for a specific selection.
     */
    public function forSelection(Selection $selection): static
    {
        return $this->state(fn (array $attributes) => [
            'taggable_id' => $selection->id,
            'taggable_type' => Selection::class,
        ]);
    }

    /**
     * Create taggable for anime only.
     */
    public function animeOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'taggable_id' => Anime::factory(),
            'taggable_type' => Anime::class,
        ]);
    }

    /**
     * Create taggable for person only.
     */
    public function personOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'taggable_id' => Person::factory(),
            'taggable_type' => Person::class,
        ]);
    }

    /**
     * Create taggable for selection only.
     */
    public function selectionOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'taggable_id' => Selection::factory(),
            'taggable_type' => Selection::class,
        ]);
    }

    /**
     * Create taggable with genre tags.
     */
    public function withGenreTag(): static
    {
        return $this->state(fn (array $attributes) => [
            'tag_id' => Tag::factory()->genre(),
        ]);
    }

    /**
     * Create taggable with non-genre tags.
     */
    public function withNonGenreTag(): static
    {
        return $this->state(fn (array $attributes) => [
            'tag_id' => Tag::factory()->nonGenre(),
        ]);
    }

    /**
     * Create multiple taggables for the same model.
     */
    public function forModel($model): static
    {
        return $this->state(fn (array $attributes) => [
            'taggable_id' => $model->id,
            'taggable_type' => get_class($model),
        ]);
    }
}
