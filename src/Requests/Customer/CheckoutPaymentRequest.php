<?php

namespace Eshop\Requests\Customer;

use Eshop\Models\Cart\CartEvent;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Validation\Validator;
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

    protected function failedValidation(Validator $validator)
    {
        $order = app(Order::class);
        CartEvent::setCheckoutPayment($order->id, CartEvent::ERROR, $validator->errors()->messages());

        parent::failedValidation($validator);
    }
}
