<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'country_shipping_method_id' => 'nullable|integer|exists:country_shipping_method,id',
            'country_payment_method_id'  => 'nullable|integer|exists:country_payment_method,id',
        ];
    }
}
