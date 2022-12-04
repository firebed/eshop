<?php

namespace Eshop\Models\Cart;

use Eshop\Models\Location\ShippingMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $cart_id
 * @property int    $courier_id
 * @property string $number
 * @property bool   $is_manual
 * @property array  $meta
 *
 * @mixin Builder
 */
class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = ['cart_id', 'courier_id', 'number', 'is_manual', 'meta'];

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