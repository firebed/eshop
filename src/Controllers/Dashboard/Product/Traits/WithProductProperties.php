<?php


namespace Eshop\Controllers\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;

trait WithProductProperties
{
    protected function prepareProperties(Product $product): array
    {
        $choices = $product->properties
            ->groupBy('id')
            ->map(fn($g) => $g->pluck('pivot.category_choice_id')->all())
            ->all();

        return compact('choices');
    }

    protected function saveProperties(Product $product, ?array $data): void
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