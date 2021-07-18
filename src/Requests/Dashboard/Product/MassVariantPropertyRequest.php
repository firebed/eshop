<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;

class MassVariantPropertyRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        $rules = [
            'ids'      => ['required', 'array', 'exists:products,id'],
            'ids.*'    => ['required', 'integer', 'distinct'],
            'property' => ['required', 'in:price,compare_price,discount,sku,stock,weight'],
            'values'   => ['required', 'array'],
        ];

        if ($this->property === 'sku') {
            foreach($this->ids as $i => $id) {
                $rules["values.$i"] = ['distinct', "unique:products,sku,$id"];
            }
        }

        return $rules;
    }
}
