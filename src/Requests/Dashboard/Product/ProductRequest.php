<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'name'                => ['required', 'string'],
            'description'         => ['nullable', 'string'],
            'category_id'         => ['required', 'integer', 'exists:categories,id'],
            'manufacturer_id'     => ['nullable', 'integer', 'exists:manufacturers,id'],

            # Pricing
            'price'               => ['required', 'numeric', 'min:0'],
            'compare_price'       => ['required', 'numeric', 'min:0'],
            'discount'            => ['required', 'numeric', 'between:0,1'],
            'vat'                 => ['nullable', 'numeric', 'exists:vats,regime'],

            # Inventory
            'is_physical'         => ['required', 'boolean'],
            'sku'                 => ['required', 'string', Rule::unique('products')->when($product, fn($q) => $q->ignore($product))],
            'mpn'                 => ['nullable', 'string'],
            'barcode'             => ['nullable', 'string', Rule::unique('products')->when($product, fn($q) => $q->ignore($product))],
            'location'            => ['nullable', 'string'],
            'stock'               => ['required', 'integer'],
            'weight'              => ['required', 'integer', 'min:0'],
            'unit_id'             => ['required', 'integer', 'exists:units,id'],

            # Accessibility
            'visible'             => ['required', 'boolean'],
            'available'           => ['required', 'boolean'],
            'available_gt'        => ['required', 'integer'],
            'display_stock'       => ['required', 'boolean'],
            'display_stock_lt'    => ['nullable', 'integer'],

            # Variants
            'variants_display'    => ['nullable', 'string', 'in:grid,buttons,list'],
            'preview_variants'    => ['required', 'boolean'],

            # Seo
            'slug'                => ['required', 'string', new Slug(), Rule::unique('products')->when($product, fn($q) => $q->ignore($product))],
            'seo.locale'          => ['required', 'string', 'size:2', 'exists:locales,name'],
            'seo.title'           => ['required', 'string', 'max:70', Rule::unique('seo', 'title')->where('locale', app()->getLocale())->when($product, fn($q) => $q->whereNot('seo_id', $product->id))],
            'seo.description'     => ['required', 'string'],

            # Media
            'image'               => ['nullable', 'image'],
            'images'              => ['nullable', 'array'],
            'images.*'            => ['nullable', 'image'],

            # Properties / Attributes / Choices
            'properties.values'   => ['nullable', 'array'],
            'properties.choices'  => ['nullable', 'array'],

            # Variant types
            'variantTypes'        => ['nullable', 'array'],
            'variantTypes.*'      => ['required', 'array'],
            'variantTypes.*.id'   => ['nullable', 'integer', 'exists:variant_types,id'],
            'variantTypes.*.name' => ['required', 'string', 'distinct'],

            # Collections
            'collections'         => ['nullable', 'array'],
            'collections.*'       => ['required', 'integer', 'exists:collections,id'],
        ];
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'variantTypes.*.name' => 'name'
        ]);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_physical'      => $this->has('is_physical'),
            'visible'          => $this->has('visible'),
            'available'        => $this->has('available'),
            'display_stock'    => $this->has('display_stock'),
            'preview_variants' => $this->has('preview_variants'),
            'has_variants'     => $this->filled('variantTypes'),
        ]);
    }
}
