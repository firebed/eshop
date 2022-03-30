<?php

namespace Eshop\Actions\Product\Traits;

use Eshop\Models\Product\Product;
use Eshop\Models\Product\ProductVariantOption;

trait SavesVariantOptions
{
    protected function saveVariantOptions(Product $variant, array $options): void
    {
        $variant->options()->sync([]);
        
        foreach ($options as $variantTypeId => $option) {
            $model = new ProductVariantOption();
            $model->product_id = $variant->id;
            $model->variant_type_id = $variantTypeId;
            $model->slug = slugify($option, '_');
            $model->name = $option;
            $model->save();
        }
    }
}