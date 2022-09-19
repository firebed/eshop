<?php

namespace Eshop\Actions\Product\Traits;

use Eshop\Models\Product\Product;
use Eshop\Models\Product\ProductVariantOption;

trait SavesVariantOptions
{
    protected function saveVariantOptions(Product $variant, array $options): void
    {
        foreach($options as $variantTypeId => $option) {
            ProductVariantOption::updateOrCreate(
                [
                    'variant_type_id' => $variantTypeId,
                    'product_id' => $variant->id
                ],
                [
                    'name'       => $option,
                    'slug'       => slugify($option, '_')
                ]
            );
        }

        //foreach ($options as $variantTypeId => $option) {
        //    $model = new ProductVariantOption();
        //    $model->product_id = $variant->id;
        //    $model->variant_type_id = $variantTypeId;
        //    $model->slug = slugify($option, '_');
        //    $model->name = $option;
        //    $model->save();
        //}
    }
}