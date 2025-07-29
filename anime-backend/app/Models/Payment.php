<?php

namespace AnimeSite\Models;

use AnimeSite\Models\Builders\PaymentQueryBuilder;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use AnimeSite\Builders\PaymentBuilder;
use AnimeSite\Enums\PaymentStatus;

/**
 * @mixin IdeHelperPayment
 */
class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory, HasUlids;

    protected $casts = [
        'amount' => 'decimal:2',
        'liqpay_data' => 'array',
        'status' => PaymentStatus::class,
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::SUCCESS;
    }

    public function isPending(): bool
    {
        return $this->status === PaymentStatus::PENDING;
    }

    public function isFailed(): bool
    {
        return $this->status === PaymentStatus::FAILED;
    }

    public function isRefunded(): bool
    {
        return $this->status === PaymentStatus::REFUNDED;
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return PaymentQueryBuilder
     */
    public function newEloquentBuilder($query): PaymentQueryBuilder
    {
        return new PaymentQueryBuilder($query);
    }
}
