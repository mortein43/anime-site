<?php

namespace AnimeSite\Models;

use AnimeSite\Builders\SelectionBuilder;
use AnimeSite\Models\Builders\SelectionQueryBuilder;
use AnimeSite\Models\Traits\HasSearchable;
use Database\Factories\SelectionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use AnimeSite\Models\Traits\HasFiles;
use AnimeSite\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperSelection
 */
class Selection extends Model
{
    /** @use HasFactory<SelectionFactory> */
    use HasFactory, HasSeo, HasUlids, HasFiles, HasSearchable;

    protected $fillable = [
        'user_id',
        'slug',
        'name',
        'description',
        'is_published',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_image',
    ];

    protected $hidden = ['searchable'];

    protected $casts = [
        'is_published' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function animes(): MorphToMany
    {
        return $this->morphedByMany(Anime::class, 'selectionable', 'selectionables');
    }

    public function persons(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'selectionable', 'selectionables');
    }

    public function episodes(): MorphToMany
    {
        return $this->morphedByMany(Episode::class, 'selectionable', 'selectionables');
    }

    public function userLists(): MorphMany
    {
        return $this->morphMany(UserList::class, 'listable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Зв'язок з тегами (поліморфний)
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables');
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return SelectionQueryBuilder
     */
    public function newEloquentBuilder($query): SelectionQueryBuilder
    {
        return new SelectionQueryBuilder($query);
    }
}
