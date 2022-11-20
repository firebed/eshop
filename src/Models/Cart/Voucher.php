<?php

namespace Eshop\Models\Cart;

use Eshop\Models\Location\ShippingMethod;
use Eshop\Services\Courier\Couriers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int                $id
 * @property int                $cart_id
 * @property Couriers           $courier
 * @property string             $number
 * @property bool               $is_manual
 * @property array              $meta
 *
 * @property Collection<Pickup> $pickups
 *
 * @mixin Builder
 */
class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = ['cart_id', 'courier', 'number', 'is_manual', 'meta'];

    protected $casts = [
        'is_manual' => 'bool',
        'meta'      => 'array'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function pickups(): BelongsToMany
    {
        return $this->belongsToMany(Pickup::class);
    }

    public function isActive(): Attribute
    {
        return new Attribute(
            get: fn() => $this->deleted_at === null,
        );
    }

    public function isDeleted(): Attribute
    {
        return new Attribute(
            get: fn() => $this->deleted_at !== null,
        );
    }
}