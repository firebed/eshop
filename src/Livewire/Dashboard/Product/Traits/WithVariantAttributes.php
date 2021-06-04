<?php


namespace Ecommerce\Livewire\Dashboard\Product\Traits;


use Illuminate\Validation\Rule;

trait WithVariantAttributes
{
    protected function rules(): array
    {
        $rules = [
            'variant.id'               => ['nullable', 'int'],
            'variant.parent_id'        => ['required', 'int'],
            'variant.category_id'      => ['required', 'int'],
            'variant.manufacturer_id'  => ['nullable', 'int'],

            // Pricing
            'variant.price'            => ['required', 'numeric', 'min:0'],
            'variant.compare_price'    => ['required', 'numeric', 'min:0'],
            'variant.discount'         => ['required', 'numeric', 'between:0,1'],
            'variant.vat'              => ['required', 'numeric'],

            // Shipping
            'variant.weight'           => ['required', 'numeric', 'min:0'],

            // Inventory
            'variant.sku'              => ['required', 'string'],
            'variant.barcode'          => ['nullable', 'string'],
            'variant.location'         => ['nullable', 'string'],
            'variant.stock'            => ['required', 'numeric', 'min:0'],
            'variant.unit_id'          => ['required', 'int'],

            // Accessibility
            'variant.visible'          => ['required', 'boolean'],
            'variant.available'        => ['required', 'boolean'],
            'variant.available_gt'     => ['nullable', 'integer'],
            'variant.display_stock'    => ['required', 'boolean'],
            'variant.display_stock_lt' => ['nullable', 'integer'],

            // Variant values - options
            'variant_values'           => ['required', 'array'],
            'variant_values.*'         => ['required', 'string'],
        ];

        $rules['variant.slug'] = $this->variant->id
            ? ['required', 'string', 'max:70', Rule::unique('products', 'slug')->ignore($this->variant)]
            : ['required', 'string', 'max:70', Rule::unique('products', 'slug')];

        return $rules;
    }
}
