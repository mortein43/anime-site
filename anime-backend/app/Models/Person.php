<?php

namespace AnimeSite\Models;

use AnimeSite\Builders\PersonBuilder;
use AnimeSite\Models\Builders\PersonQueryBuilder;
use AnimeSite\Models\Traits\HasSearchable;
use AnimeSite\Models\Traits\HasUserInteractions;
use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use AnimeSite\Enums\Gender;
use AnimeSite\Enums\PersonType;
use AnimeSite\Models\Traits\HasFiles;
use AnimeSite\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperPerson
 */
class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
    use /*HasFactory,*/  HasSeo, HasUlids, HasFiles, HasSearchable, HasUserInteractions;

    protected $casts = [
        'type' => PersonType::class,
        'image' =>  'string',
        'gender' => Gender::class,
        'birthday' => 'date',
    ];

    public function animes(): BelongsToMany
    {
        return $this->belongsToMany(Anime::class, 'anime_person', 'person_id', 'anime_id')
            ->withPivot('character_name', 'voice_person_id');
    }


    // Якщо потрібно отримати акторів озвучки для персонажа
    public function voiceActor()
    {
        return $this->belongsToMany(Person::class, 'anime_person', 'person_id', 'voice_person_id')
            ->withPivot('anime_id', 'character_name');
    }
    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function selections(): MorphToMany
    {
        return $this->morphToMany(Selection::class, 'selectionable', 'selectionables');
    }

    /**
     * Зв'язок з тегами (поліморфний)
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables');
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->original_name
                ? "{$this->name} ({$this->original_name})"
                : $this->name,
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birthday
                ? now()->diffInYears($this->birthday)
                : null,
        );
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \AnimeSite\Models\Builders\PersonQueryBuilder
     */
    public function newEloquentBuilder($query): PersonQueryBuilder
    {
        return new PersonQueryBuilder($query);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function setImageAttribute($value)
    {
        // Якщо $value вже повний URL — просто зберігаємо
        if (str_starts_with($value, 'https://')) {
            $this->attributes['image'] = $value;
            return;
        }

        // Інакше формуємо повний URL за ключем файлу
        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');

        $this->attributes['image'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
        $this->attributes['meta_image'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
    }

}
