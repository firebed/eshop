<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge([
            'shipping_method_id' => ['required', 'integer', 'exists:shipping_methods,id'],
            'payment_method_id'  => ['required', 'integer', 'exists:payment_methods,id'],
        ]);
    }
}
