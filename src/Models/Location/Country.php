<?php

namespace Eshop\Models\Location;

use Eshop\Models\Cart\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Country
 * @package App\Models
 *
 * @property integer    id
 * @property string     name
 * @property string     code
 * @property ?string    timezone
 * @property boolean    shippable
 *
 * @property Collection cities
 * @property Collection regions
 * @property Collection paymentMethods
 * @property Collection shippingMethods
 *
 * @mixin Builder
 */
class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'timezone', 'shippable', 'visible'];

    protected $casts = [
        'visible' => 'bool'
    ];

    public static function scopeCode(Builder $builder, string $code): Builder
    {
        return $builder->where('code', $code);
    }

    public static function default(): null|Country
    {
        return self::firstWhere('code', eshop('country'));
    }

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class)
            ->using(CountryPaymentMethod::class)
            ->withPivot('id', 'fee', 'cart_total', 'position', 'visible')
            ->orderByPivot('position')
            ->withTimestamps();
    }

    public function shippingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class)
            ->using(CountryShippingMethod::class)
            ->withPivot('id', 'fee', 'cart_total', 'weight_limit', 'weight_excess_fee', 'inaccessible_area_fee', 'position', 'visible')
            ->orderByPivot('position')
            ->withTimestamps();
    }

    public function paymentOptions(): HasMany
    {
        return $this->hasMany(CountryPaymentMethod::class);
    }

    public function shippingOptions(): HasMany
    {
        return $this->hasMany(CountryShippingMethod::class);
    }

    public function filterShippingOptions(float $products_value): Collection
    {
        return $this->shippingOptions
            ->where('visible', true)
            ->where('cart_total', '<=', $products_value)
            ->sortBy('position')
            ->sortByDesc('cart_total')
            ->groupBy('shipping_method_id')
            ->map(function ($c) {
                return $c->groupBy(fn($g) => $g->inaccessible_area_fee > 0 ? 'inaccessible_area' : 'accessible_area')
                    ->map(fn($i) => $i->unique('shipping_method_id'));
            })
            ->flatten();
    }

    public function filterPaymentOptions(float $products_value): Collection
    {
        return $this->paymentOptions
            ->where('visible', true)
            ->where('cart_total', '<=', $products_value)
            ->sortBy('position')
            ->groupBy('payment_method_id')
            ->map(fn($g) => $g->sortByDesc('cart_total')->unique('payment_method_id'))
            ->collapse();
    }

    public function submittedCarts(): HasMany
    {
        return $this->hasMany(Cart::class)->whereNotNull('submitted_at');
    }

    public function scopeVisible(Builder $builder): Builder
    {
        return $builder->where('visible', true);
    }
}
