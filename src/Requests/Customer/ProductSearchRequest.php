<?php

namespace Eshop\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer_ids' => ['nullable', 'string'],
            'min_price'        => ['nullable', 'numeric', 'min:0'],
            'max_price'        => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
