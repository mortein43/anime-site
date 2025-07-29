<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\TariffQueryBuilder;
use Database\Factories\TariffFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use AnimeSite\Builders\TariffBuilder;
use AnimeSite\Enums\TariffFeature;
use AnimeSite\Models\Traits\HasSeo;

/**
 * @mixin IdeHelperTariff
 */
class Tariff extends Model
{
    /** @use HasFactory<TariffFactory> */
    use HasFactory, HasSeo, HasUlids;

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'json',
        'is_active' => 'boolean',
    ];

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->price, 2) . ' ' . $this->currency
        );
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return TariffQueryBuilder
     */
    public function newEloquentBuilder($query): TariffQueryBuilder
    {
        return new TariffQueryBuilder($query);
    }
}
