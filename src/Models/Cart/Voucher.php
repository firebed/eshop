<?php

namespace Eshop\Models\Cart;

use Carbon\Carbon;
use Eshop\Models\Location\ShippingMethod;
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
 * @property int                $shipping_method_id
 * @property string             $number
 * @property bool               $is_manual
 *
 * @property ShippingMethod     $shippingMethod
 * @property Collection<Pickup> $pickups
 *
 * @mixin Builder
 */
class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = ['cart_id', 'shipping_method_id', 'number', 'is_manual'];

    protected $casts = [
        'is_manual'    => 'bool',
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