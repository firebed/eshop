<?php

namespace Eshop\Models\Product;

use Eshop\Models\Lang\Traits\HasTranslations;
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
    use HasFactory, HasTranslations;
        
    public $timestamps = FALSE;
    
    public $incrementing = true;
    
    protected $table = 'product_variant_type';
    
    public $translatable = ['name'];
}
