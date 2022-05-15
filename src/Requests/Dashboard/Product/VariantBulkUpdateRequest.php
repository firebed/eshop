<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class VariantBulkUpdateRequest extends FormRequest
{
    use WithRequestNotifications;

    private const PROPERTIES = ['price', 'compare_price', 'discount', 'sku', 'mpn', 'stock', 'weight', 'display_stock_lt', 'available_gt'];

    public function authorize(): bool
    {
        return true;
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
                case 'mpn':
                    $rules["bulk_mpn.*"] = ['required', 'distinct'];
                    foreach ($this->input('bulk_ids', []) as $i => $id) {
                        $rules["bulk_mpn.$i"] = ['nullable', 'distinct', "unique:products,mpn,$id"];
                    }
                    break;
                case 'stock':
                    $rules["bulk_stock.*"] = ['required', 'integer'];
                    break;
                case 'weight':
                    $rules["bulk_weight.*"] = ['required', 'integer', 'min:0'];
                    break;
                case 'display_stock_lt':
                case 'available_gt':
                    $rules["bulk_$this->property.*"] = ['nullable', 'integer'];
                    break;
            }
        }

        return $rules;
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'bulk_price.*'            => 'price',
            'bulk_compare_price.*'    => 'compare_price',
            'bulk_discount.*'         => 'discount',
            'bulk_sku.*'              => 'sku',
            'bulk_mpn.*'              => 'mpn',
            'bulk_stock.*'            => 'stock',
            'bulk_weight.*'           => 'weight',
            'bulk_display_stock_lt.*' => 'display_stock_lt',
            'bulk_available_gt.*'     => 'available_gt'
        ]);
    }

    protected function prepareForValidation(): void
    {
        $this->properties = Arr::wrap($this->input('properties') ?? self::PROPERTIES);

        if ($this->filled('bulk_discount')) {
            $discounts = $this->bulk_discount;

            array_walk($discounts, fn(&$d) => $d = round($d/100, 2));
            $this->merge(['bulk_discount' => $discounts]);
        }
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
