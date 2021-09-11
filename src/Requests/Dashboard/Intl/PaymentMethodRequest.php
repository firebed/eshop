<?php

namespace Eshop\Requests\Dashboard\Intl;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $method = $this->route('payment_method');

        return [
            'name'                     => ['required', 'string', Rule::unique('payment_methods')->when($method, fn($q) => $q->ignore($method))],
            'show_total_on_order_form' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'show_total_on_order_form' => $this->has('show_total_on_order_form'),
        ]);
    }
}
