<?php


namespace Ecommerce\Livewire\Dashboard\Product\Traits;


use Illuminate\Validation\Rule;

trait WithProductAttributes
{
    protected function rules(): array
    {
        $rules = [
            'name'        => ['required', 'string'],
            'description' => ['nullable', 'string'],

            'product.id'               => ['nullable', 'int'],

            // Organization
            'product.category_id'      => ['required', 'int'],
            'product.manufacturer_id'  => ['nullable', 'int'],

            // Accessibility
            'product.visible'          => ['required', 'boolean'],
            'product.available'        => ['required', 'boolean'],
            'product.available_gt'     => ['nullable', 'integer'],
            'product.display_stock'    => ['required', 'boolean'],
            'product.display_stock_lt' => ['nullable', 'integer'],

            // Pricing
            'product.price'            => ['required', 'numeric', 'min:0'],
            'product.compare_price'    => ['required', 'numeric', 'min:0'],
            'product.discount'         => ['required', 'numeric', 'min:0'],
            'product.vat'              => ['required', 'numeric', 'min:0'],

            // Inventory
            'product.weight'           => ['required', 'numeric', 'min:0'],
            'product.unit_id'          => ['required', 'int', 'exists:units,id'],
            'product.sku'              => ['nullable', 'string'],
            'product.barcode'          => ['nullable', 'string'],
            'product.location'         => ['nullable', 'string'],
            'product.stock'            => ['required', 'numeric']
        ];

        $rules['product.slug'] = $this->product->id
            ? ['required', 'string', 'max:70', Rule::unique('products', 'slug')->ignore($this->product)]
            : ['required', 'string', 'max:70', Rule::unique('products', 'slug')];

        return $rules;
    }

}
