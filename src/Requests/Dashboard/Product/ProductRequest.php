<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\SeoTitle;
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
            'name'                       => ['required', 'string'],
            'description'                => ['nullable', 'string'],
            'category_id'                => ['required', 'integer', 'exists:categories,id'],
            'manufacturer_id'            => ['nullable', 'integer', 'exists:manufacturers,id'],

            # Pricing
            'price'                      => ['required', 'numeric', 'min:0'],
            'wholesale_price'            => ['required', 'numeric', 'min:0'],
            'compare_price'              => ['required', 'numeric', 'min:0'],
            'discount'                   => ['required', 'numeric', 'between:0,1'],
            'vat'                        => ['nullable', 'numeric', 'exists:vats,regime'],

            # Channel pricing
            'channel_pricing'            => ['nullable', 'array'],
            'channel_pricing.*.distinct' => ['nullable'],
            'channel_pricing.*.price'    => ['required_with:channel_pricing.*.distinct', 'numeric', 'min:0'],
            'channel_pricing.*.discount' => ['required_with:channel_pricing.*.distinct', 'numeric', 'between:0,100'],

            # Inventory
            'is_physical'                => ['required', 'boolean'],
            'sku'                        => ['required', 'string', Rule::unique('products')->when($product, fn($q) => $q->ignore($product))],
            'mpn'                        => ['nullable', 'string'],
            'barcode'                    => ['nullable', 'string', Rule::unique('products')->when($product, fn($q) => $q->ignore($product))],
            'location'                   => ['nullable', 'string'],
            'stock'                      => ['required', 'integer'],
            'weight'                     => ['required', 'integer', 'min:0'],
            'unit_id'                    => ['required', 'integer', 'exists:units,id'],

            # Accessibility
            'visible'                    => ['required', 'boolean'],
            'recent'                     => ['required', 'boolean'],
            'promote'                    => ['required', 'boolean'],
            'available'                  => ['required', 'boolean'],
            'available_gt'               => ['nullable', 'integer'],
            'display_stock'              => ['required', 'boolean'],
            'display_stock_lt'           => ['nullable', 'integer'],

            # Variants
            'variants_display'           => ['nullable', 'string', 'in:grid,buttons,list'],
            'preview_variants'           => ['required', 'boolean'],
            'variants_prefix'            => ['nullable', 'string'],

            # Seo
            'slug'                       => ['required', 'string', new Slug(), Rule::unique('products')->when($product, fn($q) => $q->ignore($product))],
            'seo.locale'                 => ['required', 'string', 'size:2', 'exists:locales,name'],
            'seo.title'                  => ['required', 'string', 'max:70', new SeoTitle($product ?? 'product')],
            'seo.description'            => ['required', 'string'],

            # Media
            'image'                      => ['nullable', 'image'],
            'images'                     => ['nullable', 'array'],
            'images.*'                   => ['nullable', 'image'],
            'has_watermark'              => ['required', 'bool'],
            
            # Attachments
            'attachment'                 => ['required', 'array'],
            'attachment.title'           => ['required_with:attachment', 'string'],
            'attachment.file'            => ['nullable', 'file', 'mimetypes:application/pdf,image/jpeg,image/png,image/gif', 'max:3072'],
            
            # Properties / Attributes / Choices
            'properties.values'          => ['nullable', 'array'],
            'properties.choices'         => ['nullable', 'array'],

            # Variant types
            'variantTypes'               => ['nullable', 'array'],
            'variantTypes.*'             => ['required', 'array'],
            'variantTypes.*.id'          => ['nullable', 'integer', 'exists:variant_types,id'],
            'variantTypes.*.name'        => ['required', 'string', 'distinct'],

            # Collections
            'collections'                => ['nullable', 'array'],
            'collections.*'              => ['required', 'integer', 'exists:collections,id'],

            'channels'   => ['nullable', 'array'],
            'channels.*' => ['required', 'integer', 'exists:channels,id'],
        ];
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'channel_pricing.*.distinct'        => 'διακριτή τιμή στο κανάλι',
            'channel_pricing.*.price'           => 'τιμή',
            'channel_pricing.*.discount'        => 'έκπτωση',
            'variantTypes.*.name'               => 'name'
        ]);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_physical'      => $this->boolean('is_physical'),
            'visible'          => $this->boolean('visible'),
            'recent'           => $this->boolean('recent'),
            'promote'          => $this->boolean('promote'),
            'available'        => $this->boolean('available'),
            'display_stock'    => $this->boolean('display_stock'),
            'preview_variants' => $this->boolean('preview_variants'),
            'has_variants'     => $this->filled('variantTypes'),
            'has_watermark'    => $this->boolean('has_watermark'),
        ]);
    }
}
