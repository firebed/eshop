<?php

namespace Eshop\Requests\Dashboard\Intl;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShippingMethodRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->route('shipping_method');

        return [
            'name'         => ['required', 'string', Rule::unique('shipping_methods')->when($method, fn($q) => $q->ignore($method))],
            'tracking_url' => ['nullable', 'string'],
            'is_courier'   => ['required', 'bool',],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_courier' => $this->has('is_courier'),
        ]);
    }
}
