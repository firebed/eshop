<?php

namespace Eshop\Actions\Product\Traits;

use Eshop\Models\Product\Product;

trait SavesProductProperties
{
    public function saveProperties(Product $product, ?array $data): void
    {
        $product->properties()->sync([]);

        if (empty($data)) {
            return;
        }

        $properties = collect($data)->mapWithKeys(fn($v) => $v)->filter();

        foreach ($properties as $propertyId => $choices) {
            if (is_array($choices)) {
                foreach ($choices as $choice) {
                    $product->properties()->attach($propertyId, ['category_choice_id' => $choice]);
                }
            } else {
                $product->properties()->attach($propertyId, ['value' => $choices]);
            }
        }
    }
}