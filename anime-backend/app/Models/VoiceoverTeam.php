<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\StudioQueryBuilder;
use AnimeSite\Models\Builders\VoiceoverTeamBuilder;
use AnimeSite\Models\Traits\HasSearchable;
use Database\Factories\VoiceoverTeamFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AnimeSite\Models\Traits\HasFiles;
use AnimeSite\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperVoiceoverTeam
 */
class VoiceoverTeam extends Model
{
    /** @use HasFactory<VoiceoverTeamFactory> */
    use HasFactory, HasSeo, HasUlids, HasFiles, HasSearchable;

    protected $hidden = ['searchable'];
    protected $table = 'voiceover_teams';

    public function newEloquentBuilder($query): VoiceoverTeamBuilder
    {
        return new VoiceoverTeamBuilder($query);
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
