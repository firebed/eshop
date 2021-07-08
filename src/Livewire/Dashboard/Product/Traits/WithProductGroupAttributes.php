<?php


namespace Eshop\Livewire\Dashboard\Product\Traits;


use Illuminate\Validation\Rule;

trait WithProductGroupAttributes
{
    protected function rules(): array
    {
        $rules = [
            'product.id'               => ['nullable', 'int'],
            'name'                     => ['required', 'string'],
            'description'              => ['nullable', 'string'],

            // Organization
            'product.category_id'      => ['required', 'int', 'exists:categories,id'],
            'product.manufacturer_id'  => ['nullable', 'int', 'exists:manufacturers,id'],

            // Variants
            'product.has_variants'     => ['required', 'boolean'],

            // Accessibility
            'product.visible'          => ['required', 'boolean'],
            'product.variants_display' => ['required', 'string', 'in:Grid,Buttons,Dropdown'],
            'product.preview_variants' => ['required', 'boolean'],
        ];

        $rules['product.slug'] = $this->product
            ? ['required', 'string', 'max:70', Rule::unique('products', 'slug')->ignore($this->product)]
            : ['required', 'string', 'max:70', Rule::unique('products', 'slug')];

        return $rules;
    }
}
