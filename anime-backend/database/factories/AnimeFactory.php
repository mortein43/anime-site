<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use AnimeSite\Enums\ApiSourceName;
use AnimeSite\Enums\AttachmentType;
use AnimeSite\Enums\Country;
use AnimeSite\Enums\Kind;
use AnimeSite\Enums\AnimeRelateType;
use AnimeSite\Enums\Period;
use AnimeSite\Enums\RestrictedRating;
use AnimeSite\Enums\Source;
use AnimeSite\Enums\Status;
use AnimeSite\Models\Anime;
use AnimeSite\Models\Studio;
use AnimeSite\ValueObjects\ApiSource;
use AnimeSite\ValueObjects\Attachment;
use AnimeSite\ValueObjects\AnimeRelate;

class AnimeFactory /*extends Factory*/
{
//    protected $model = Anime::class;
//
//    public function definition()
//    {
//        $name = $this->faker->sentence(3);
//        $studio = Studio::query()->inRandomOrder()->first() ?? Studio::factory()->create();
//
//        return [
//            'id' => Str::ulid(),
//            'slug' => Str::slug($name),
//            'name' => $name,
//            'description' => $this->faker->paragraph(),
//            //'image_name' => $this->faker->imageUrl(640, 480, 'anime', true, 'Anime Image'),
//            'aliases' => $this->faker->words(3), // зберігаємо як масив, Laravel кастує в JSON
//            'api_sources' => $this->generateApiSources(), // масив асоціативних масивів
//            'studio_id' => $studio->id,
//            'countries' => $this->generateCountries(),
//            'poster' => $this->faker->imageUrl(300, 450, 'anime', true, 'Poster'),
//            'duration' => $this->faker->numberBetween(20, 120), // Duration in minutes
//            'episodes_count' => $this->faker->numberBetween(1, 100),
//            'first_air_date' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
//            'last_air_date' => $this->faker->boolean() ? $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d') : null,
//            'imdb_score' => $this->faker->randomFloat(2, 1, 10),
//            'attachments' => $this->generateAttachments(),
//            'related' => $this->generateRelated(),
//            'similars' => $this->generateSimilars(),
//            'is_published' => $this->faker->boolean(80),
//            'meta_title' => $this->faker->sentence(6),
//            'meta_description' => $this->faker->text(150),
//            'meta_image' => $this->faker->imageUrl(1200, 630, 'seo', true, 'SEO Image'),
//            'kind' => $this->faker->randomElement(Kind::cases())->value,
//            'status' => $this->faker->randomElement(Status::cases())->value,
//            'period' => $this->faker->randomElement(array_merge(Period::cases(), [null]))?->value,
//            'restricted_rating' => $this->faker->randomElement(RestrictedRating::cases())->value,
//            'source' => $this->faker->randomElement(Source::cases())->value,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ];
//    }
//
//    /**
//     * Генерує масив api_sources як асоціативних масивів.
//     */
//    private function generateApiSources(): array
//    {
//        $sources = $this->faker->randomElements(ApiSourceName::cases(), $this->faker->numberBetween(1, 3));
//        return array_map(fn($source) => [
//            'source' => strtolower($source->name), // ключ source
//            'id' => $this->faker->uuid(),
//        ], $sources);
//    }
//
//    /**
//     * Генерує масив країн як рядки кодів.
//     */
//    private function generateCountries(): array
//    {
//        // Вибираємо випадкову кількість (1-3) випадкових значень enum Country
//        $cases = Country::cases();
//
//        $count = $this->faker->numberBetween(1, 3);
//
//        // Випадкові елементи enum
//        $randomCases = $this->faker->randomElements($cases, $count);
//
//        // Витягуємо значення (value) enum
//        return array_map(fn(Country $country) => $country->value, $randomCases);
//    }
//
//    /**
//     * Генерує масив attachments як асоціативних масивів.
//     */
//    private function generateAttachments(): array
//    {
//        $attachments = [];
//        $count = $this->faker->numberBetween(0, 3);
//
//        for ($i = 0; $i < $count; $i++) {
//            $attachments[] = [
//                'type' => $this->faker->randomElement(AttachmentType::cases())->value,
//                'src' => $this->faker->url(),
//                'title' => $this->faker->sentence(4),
//                'duration' => $this->faker->numberBetween(30, 300), // в секундах
//            ];
//        }
//
//        return $attachments;
//    }
//
//    /**
//     * Генерує масив пов’язаних аніме (related) як асоціативних масивів.
//     */
//    private function generateRelated(): array
//    {
//        $related = [];
//        $count = $this->faker->numberBetween(0, 2);
//
//        for ($i = 0; $i < $count; $i++) {
//            $related[] = [
//                'anime_id' => Str::ulid(), // унікальний ULID
//                'type' => $this->faker->randomElement(AnimeRelateType::cases())->value,
//            ];
//        }
//
//        return $related;
//    }
//
//    /**
//     * Генерує масив схожих аніме (similars) як масив рядків ULID.
//     */
//    private function generateSimilars(): array
//    {
//        $similars = [];
//        $count = $this->faker->numberBetween(0, 4);
//
//        for ($i = 0; $i < $count; $i++) {
//            $similars[] = Str::ulid();
//        }
//
//        return $similars;
//    }
//
//    /**
//     * Встановити студію для аніме.
//     */
//    public function forStudio(Studio $studio): self
//    {
//        return $this->state(fn() => [
//            'studio_id' => $studio->id,
//        ]);
//    }
//
//    /**
//     * Встановити kind.
//     */
//    public function withKind(Kind $kind): self
//    {
//        return $this->state(fn() => [
//            'kind' => $kind->value,
//        ]);
//    }
//
//    /**
//     * Встановити status.
//     */
//    public function withStatus(Status $status): self
//    {
//        return $this->state(fn() => [
//            'status' => $status->value,
//        ]);
//    }
//
//    /**
//     * Встановити is_published у true.
//     */
//    public function published(): self
//    {
//        return $this->state(fn() => [
//            'is_published' => true,
//        ]);
//    }
}
