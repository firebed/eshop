<?php


namespace App\Http\Livewire\Dashboard\Product\Traits;


use Illuminate\Validation\Rule;

trait WithProductGroupAttributes
{
    protected function rules(): array
    {
        $rules = [
            'product.id'              => ['nullable', 'int'],
            'name'                    => ['required', 'string'],
            'description'             => ['nullable', 'string'],

            // Organization
            'product.category_id'     => ['required', 'int', 'exists:categories,id'],
            'product.manufacturer_id' => ['nullable', 'int', 'exists:manufacturers,id'],

            // Accessibility
            'product.visible'         => ['required', 'boolean'],
        ];

        $rules['product.slug'] = $this->product
            ? ['required', 'string', 'max:70', Rule::unique('products', 'slug')->ignore($this->product)]
            : ['required', 'string', 'max:70', Rule::unique('products', 'slug')];

        return $rules;
    }
}
