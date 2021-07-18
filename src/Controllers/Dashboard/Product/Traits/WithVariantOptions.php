<?php


namespace Eshop\Controllers\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;

trait WithVariantOptions
{
    protected function saveVariantOptions(Product $variant, array $options): void
    {
        foreach ($options as $variantTypeId => $option) {
            $variant->options()->attach($variantTypeId, [
                'value' => $option,
                'slug'  => slugify($option)
            ]);
        }
    }
}