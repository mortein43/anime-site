<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\RatingQueryBuilder;
use Database\Factories\RatingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use AnimeSite\Builders\RatingBuilder;

/**
 * @mixin IdeHelperRating
 */
class Rating extends Model
{
    /** @use HasFactory<RatingFactory> */
    use /*HasFactory,*/  HasUlids;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    public function review(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => nl2br(e($attributes['review'])),
            set: fn (mixed $value) => trim($value)
        );
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return RatingQueryBuilder
     */
    public function newEloquentBuilder($query): RatingQueryBuilder
    {
        return new RatingQueryBuilder($query);
    }
}
