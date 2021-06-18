<?php

namespace Eshop\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CartProduct
 * @package App\Models\Cart
 *
 * @property string value
 * @property string slug
 *
 * @mixin Builder
 */
class ProductVariantOption extends Pivot
{
    use HasFactory;
}
