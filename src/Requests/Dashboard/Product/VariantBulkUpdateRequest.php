<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class VariantBulkUpdateRequest extends FormRequest
{
    use WithRequestNotifications;

    private const PROPERTIES = ['price', 'compare_price', 'discount', 'sku', 'stock', 'weight'];

    public function authorize(): bool
    {
        return TRUE;
    }

    protected function prepareForValidation(): void
    {
        $this->properties = Arr::wrap($this->input('properties') ?? self::PROPERTIES);
    }

    public function rules(): array
    {
        $rules = [
            'properties'   => ['nullable', 'array'],
            'properties.*' => ['nullable', Rule::in(self::PROPERTIES)],
            'bulk_ids'     => ['required', 'array', 'exists:products,id'],
            'bulk_ids.*'   => ['required', 'integer', 'distinct'],
        ];

        foreach ($this->properties as $property) {
            $rules["bulk_$property"] = ['required', 'array'];

            switch ($property) {
                case 'price':
                case 'compare_price':
                    $rules["bulk_$this->property.*"] = ['required', 'numeric', 'min:0'];
                    break;
                case 'sku':
                    $rules["bulk_sku.*"] = ['required', 'distinct'];
                    foreach ($this->input('bulk_ids', []) as $i => $id) {
                        $rules["bulk_sku.$i"] = ['required', 'distinct', "unique:products,sku,$id"];
                    }
                    break;
                case 'stock':
                    $rules["bulk_stock.*"] = ['required', 'integer'];
                    break;
                case 'weight':
                    $rules["bulk_weight.*"] = ['required', 'integer', 'min:0'];
                    break;
            }
        }

        return $rules;
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'bulk_price.*'         => 'price',
            'bulk_compare_price.*' => 'compare_price',
            'bulk_discount.*'      => 'discount',
            'bulk_sku.*'           => 'sku',
            'bulk_stock.*'         => 'stock',
            'bulk_weight.*'        => 'weight',
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return route('variants.bulk-edit', [
            $this->route('product'),
            'properties' => $this->properties,
            'ids'        => $this->input('bulk_ids', [])
        ]);
    }
}
