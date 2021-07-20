<?php


namespace Eshop\Controllers\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;

trait WithProductProperties
{
    protected function prepareProperties(Product $product): array
    {
        $values = $product->properties
            ->reject
            ->isValueRestricted()
            ->pluck('pivot.value', 'id')->all();

        $choices = $product->properties
            ->filter->isValueRestricted()
            ->groupBy('id')
            ->map(fn($g) => $g->pluck('pivot.category_choice_id')->all())
            ->all();

        return compact('values', 'choices');
    }

    protected function saveProperties(Product $product, array $data): void
    {
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