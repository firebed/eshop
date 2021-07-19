<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;

class VariantBulkCreateRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'variants'             => ['required', 'array'],
            'variants.*.options'   => ['required', 'array'],
            'variants.*.options.*' => ['required', 'string'],
            'variants.*.price'     => ['required', 'numeric', 'min:0'],
            'variants.*.stock'     => ['required', 'numeric'],
            'variants.*.sku'       => ['required', 'string', 'unique:products,sku'],
            'variants.*.barcode'   => ['nullable', 'string', 'unique:products,barcode'],
        ];
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'variants.*.options.*' => 'options',
            'variants.*.price'     => 'price',
            'variants.*.stock'     => 'stock',
            'variants.*.sku'       => 'sku',
            'variants.*.barcode'   => 'barcode',
        ]);
    }
}
