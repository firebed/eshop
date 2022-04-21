<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\SeoTitle;
use Eshop\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;

class ProductCopyRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string'],

            # Pricing
            'price'         => ['required', 'numeric', 'min:0'],
            'compare_price' => ['required', 'numeric', 'min:0'],

            # Inventory
            'sku'           => ['required', 'string', 'unique:products'],

            # Seo
            'slug'          => ['required', 'string', new Slug(), 'unique:products'],
            'seo.title'     => ['required', 'string', 'max:70', new SeoTitle('product')],
        ];
    }
}
