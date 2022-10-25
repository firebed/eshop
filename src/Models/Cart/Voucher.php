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
 * @property Carbon             $cancelled_at
 *
 * @property ShippingMethod     $shippingMethod
 * @property Collection<Pickup> $pickups
 *
 * @method Builder onWait(Builder $builder)
 * @method Builder active(Builder $builder)
 *
 * @mixin Builder
 */
class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = ['cart_id', 'shipping_method_id', 'number', 'is_manual', 'cancelled_at'];

    protected $casts = [
        'is_manual'    => 'bool',
        'cancelled_at' => 'datetime'
    ];

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
            get: fn() => $this->cancelled_at === null,
        );
    }

    public function isCancelled(): Attribute
    {
        return new Attribute(
            get: fn() => $this->cancelled_at !== null,
        );
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at');
    }

    public function scopeOnWait(Builder $builder): Builder
    {
        return $builder->whereNull('pickup_id');
    }
}