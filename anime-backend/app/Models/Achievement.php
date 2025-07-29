<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\AchievementQueryBuilder;
use AnimeSite\Models\Traits\HasSeo;
use Database\Factories\AchievementFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use AnimeSite\Builders\AchievementBuilder;

/**
 * @mixin IdeHelperAchievement
 */
class Achievement extends Model
{
    /** @use HasFactory<AchievementFactory> */
    use HasFactory, HasUlids, HasSeo;
    public $timestamps = false;

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class)
            ->withPivot('progress_count');
    }

    public function newEloquentBuilder($query): AchievementQueryBuilder
    {
        return new AchievementQueryBuilder($query);
    }

    public function setIconAttribute($value)
    {
        // Якщо $value вже повний URL — просто зберігаємо
        if (str_starts_with($value, 'https://')) {
            $this->attributes['icon'] = $value;
            return;
        }

        // Інакше формуємо повний URL за ключем файлу
        $storageAccount = env('AZURE_STORAGE_NAME', 'storageanimesite');
        $container = env('AZURE_STORAGE_CONTAINER', 'images');

        $this->attributes['icon'] = "https://{$storageAccount}.blob.core.windows.net/{$container}/{$value}";
    }
}
