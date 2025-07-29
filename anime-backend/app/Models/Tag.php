<?php

namespace AnimeSite\Models;

use AnimeSite\Builders\TagBuilder;
use AnimeSite\Models\Builders\TagQueryBuilder;
use AnimeSite\Models\Traits\HasSearchable;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use AnimeSite\Models\Traits\HasFiles;
use AnimeSite\Models\Traits\HasSeo;
use Illuminate\Database\Query\Builder;

/**
 * @mixin IdeHelperTag
 */
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use /*HasFactory,*/  HasSeo, HasUlids, HasFiles, HasSearchable;
    protected $fillable = [
        'slug',
        'name',
        'description',
        'image',
        'aliases',
        'is_genre',
        'meta_title',
        'meta_description',
        'meta_image',
        'parent_id',
    ];

    protected $hidden = ['taggables'];

    protected $casts = [
        'aliases' => 'array',
        'is_genre' => 'boolean',
    ];


    /**
     * Зв'язок з аніме (поліморфний)
     */
    public function animes(): MorphToMany
    {
        return $this->morphedByMany(Anime::class, 'taggable', 'taggables');
    }
    public function getAnimesCountAttribute()
    {
        // Check if the count was properly loaded
        if (array_key_exists('animes_count', $this->attributes)) {
            return $this->attributes['animes_count'];
        }

        // Log where this is being accessed without being loaded
        \Log::warning('animes_count accessed without being loaded', [
            'tag_id' => $this->id,
            'tag_name' => $this->name,
            'stack_trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ]);

        // Return 0 or load dynamically (temporary fix)
        return 0;
        // Or: return $this->animes()->count(); // Not recommended for performance
    }
    /**
     * Зв'язок з персонами (поліморфний)
     */
    public function people(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'taggable', 'taggables');
    }
    public function getPeopleCountAttribute()
    {
        // Check if the count was properly loaded
        if (array_key_exists('people_count', $this->attributes)) {
            return $this->attributes['people_count'];
        }

        // Log where this is being accessed without being loaded
        \Log::warning('people_count accessed without being loaded', [
            'tag_id' => $this->id,
            'tag_name' => $this->name,
            'stack_trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ]);

        // Return 0 or load dynamically (temporary fix)
        return 0;
        // Or: return $this->animes()->count(); // Not recommended for performance
    }
    /**
     * Зв'язок з добірками (поліморфний)
     */
    public function selections(): MorphToMany
    {
        return $this->morphedByMany(Selection::class, 'taggable', 'taggables');
    }

    /**
     * Зв'язок з усіма моделями, які мають теги
     */
    public function taggables(): MorphToMany
    {
        return $this->morphedByMany(Model::class, 'taggable', 'taggables');
    }

    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  Builder  $query
     * @return TagQueryBuilder
     */
    public function newEloquentBuilder($query): TagQueryBuilder
    {
        return new TagQueryBuilder($query);
    }
}
