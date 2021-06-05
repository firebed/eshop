<?php

namespace Eshop\Models\Cart;

use Eshop\Models\Product\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CartProduct
 * @package App\Models\Cart
 *
 * @property integer product_id
 * @property integer cart_id
 * @property double  quantity
 * @property double  price
 * @property double  compare_price
 * @property double  discount
 * @property double  vat
 * @property int     injector_id
 *
 * @property double  total
 *
 * @property Cart    cart
 * @property Product product
 *
 * @mixin Builder
 */
class CartProduct extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'quantity'      => 'int',
        'price'         => 'float',
        'compare_price' => 'float',
        'discount'      => 'float',
        'vat'           => 'float',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getNetValueAttribute(): float
    {
        return round($this->quantity * $this->price * (1 - $this->discount), 2);
    }

    public function getTotalWithoutVatAttribute(): float
    {
        return number_format($this->netValue / (1 + $this->vat));
    }

    public function getVatValueAttribute(): float
    {
        return $this->total * $this->vat;
    }
}
