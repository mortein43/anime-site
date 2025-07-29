<?php

namespace AnimeSite\Models;

use AnimeSite\Builders\StudioBuilder;
use AnimeSite\Models\Builders\StudioQueryBuilder;
use AnimeSite\Models\Traits\HasSearchable;
use Database\Factories\StudioFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use AnimeSite\Models\Traits\HasFiles;
use AnimeSite\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperStudio
 */
class Studio extends Model
{
    /** @use HasFactory<StudioFactory> */
    use /*HasFactory,*/  HasSeo, HasUlids, HasFiles, HasSearchable;

    protected $hidden = ['searchable'];

    public function animes(): HasMany
    {
        return $this->hasMany(Anime::class);
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return StudioQueryBuilder
     */
    public function newEloquentBuilder($query): StudioQueryBuilder
    {
        return new StudioQueryBuilder($query);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');

        return "https://{$storageAccount}.blob.core.windows.net/{$container}/{$this->image}";
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
