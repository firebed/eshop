<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\SeoTitle;
use Eshop\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VariantRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variant = $this->route('variant');

        return [
            # Options
            'options'                    => ['required', 'array'],
            'options.*'                  => ['required', 'string'],

            # Pricing
            'price'                      => ['required', 'numeric', 'min:0'],
            'wholesale_price'            => ['required', 'numeric', 'min:0'],
            'compare_price'              => ['required', 'numeric', 'min:0'],
            'discount'                   => ['required', 'numeric', 'between:0,1'],
            'vat'                        => ['required', 'numeric', 'exists:vats,regime'],

            # Channel pricing
            'channel_pricing'            => ['nullable', 'array'],
            'channel_pricing.*.distinct' => ['nullable'],
            'channel_pricing.*.price'    => ['required_with:channel_pricing.*.distinct', 'numeric', 'min:0'],
            'channel_pricing.*.discount' => ['required_with:channel_pricing.*.distinct', 'numeric', 'between:0,100'],

            # Inventory
            'is_physical'                => ['required', 'boolean'],
            'sku'                        => ['required', 'string', Rule::unique('products')->when($variant, fn($q) => $q->ignore($variant))],
            'mpn'                        => ['nullable', 'string'],
            'barcode'                    => ['nullable', 'string', Rule::unique('products')->when($variant, fn($q) => $q->ignore($variant))],
            'location'                   => ['nullable', 'string'],
            'stock'                      => ['required', 'integer'],
            'weight'                     => ['required', 'integer', 'min:0'],
            'unit_id'                    => ['required', 'integer', 'exists:units,id'],

            # Accessibility
            'visible'                    => ['required', 'boolean'],
            'recent'                     => ['required', 'boolean'],
            'available'                  => ['required', 'boolean'],
            'available_gt'               => ['nullable', 'integer'],
            'display_stock'              => ['required', 'boolean'],
            'display_stock_lt'           => ['nullable', 'integer'],

            # SEO
            'slug'                       => ['required', 'string', new Slug(), Rule::unique('products', 'slug')->when($variant, fn($q) => $q->ignore($variant))],
            'seo.locale'                 => ['required', 'string', 'size:2', 'exists:locales,name'],
            'seo.title'                  => ['required', 'string', 'max:70', new SeoTitle($variant ?? 'variant')],
            'seo.description'            => ['nullable', 'string'],

            # Media
            'image'                      => ['nullable', 'image'],
            'has_watermark'              => ['required', 'bool'],

            'channels'   => ['nullable', 'array'],
            'channels.*' => ['required', 'integer', 'exists:channels,id'],
        ];
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'channel_pricing.*.distinct' => 'διακριτή τιμή στο κανάλι',
            'channel_pricing.*.price'    => 'τιμή',
            'channel_pricing.*.discount' => 'έκπτωση',
            'options.*'                  => 'options',
            'seo.title'                  => 'title'
        ]);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_physical'   => $this->boolean('is_physical'),
            'visible'       => $this->boolean('visible'),
            'recent'        => $this->boolean('recent'),
            'available'     => $this->boolean('available'),
            'display_stock' => $this->boolean('display_stock'),
            'has_watermark' => $this->boolean('has_watermark'),
        ]);
    }
}
