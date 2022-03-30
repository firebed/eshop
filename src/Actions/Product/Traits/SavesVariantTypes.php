<?php

namespace Eshop\Actions\Product\Traits;

use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;

trait SavesVariantTypes
{
    protected function saveVariantTypes(Product $product, array $data): void
    {
        $variantTypes = $product->variantTypes()->with('translation')->get()->pluck('name', 'id');
        $deleteIds = $variantTypes->keys()->diff(array_column($data, 'id'));
        VariantType::whereKey($deleteIds)->delete();

        foreach ($data as $i => $variantType) {
            $product->variantTypes()->updateOrCreate(['id' => $variantType['id']], [
                'name'     => $variantType['name'],
                'slug'     => slugify($variantType['name']),
                'position' => $i
            ]);
        }
    }

    protected function storeVariantTypes(Product $product, array $data): void
    {
        if (empty($data)) {
            return;
        }
        
        $variantTypes = collect($data)->transform(fn($v, $i) => new VariantType([
            'name'     => $v['name'],
            'slug'     => slugify($v['name']),
            'position' => $i
        ]));

        $product->variantTypes()->saveMany($variantTypes);
    }
}