<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;

class VariantBulkUpdateRequest extends FormRequest
{
    use WithRequestNotifications;

    protected $redirectRoute = 'variants.bulk-edit';

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        $rules = [
            'property' => ['required', 'in:price,compare_price,discount,sku,stock,weight'],

            'bulk_ids'   => ['required', 'array', 'exists:products,id'],
            'bulk_ids.*' => ['required', 'integer', 'distinct'],

            'bulk_values' => ['required', 'array']
        ];

        switch ($this->property) {
            case 'price':
            case 'compare_price':
                $rules["bulk_values.*"] = ['required', 'numeric', 'min:0'];
                break;
            case 'sku':
                foreach ($this->bulk_ids as $i => $id) {
                    $rules["bulk_values.$i"] = ['required', 'distinct', "unique:products,sku,$id"];
                }
                break;
            case 'stock':
                $rules["bulk_values.*"] = ['required', 'integer'];
                break;
            case 'weight':
                $rules["bulk_values.*"] = ['required', 'integer', 'min:0'];
                break;
        }

        return $rules;
    }
}
