<?php


namespace Eshop\Controllers\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;

trait WithVariantTypes
{
    protected function saveVariantTypes(Product $product, array $data): void
    {
        $variantTypes = collect($data)->transform(fn($v) => new VariantType([
            'name' => $v['name'],
            'slug' => slugify($v['name'])
        ]));
        $product->variantTypes()->saveMany($variantTypes);
    }

    protected function syncVariantTypes(Product $product, $data): void
    {
        $variantTypes = $product->variantTypes()->pluck('name', 'id');
        $deleteIds = $variantTypes->keys()->diff(array_column($data, 'id'));
        VariantType::whereKey($deleteIds)->delete();

        foreach ($data as $variantType) {
            $product->variantTypes()->updateOrCreate(['id' => $variantType['id']], [
                'name' => $variantType['name'],
                'slug' => slugify($variantType['name']),
            ]);
        }
    }
}