<?php

namespace App\Http\Requests\Customer;

use App\Repository\Contracts\Order;
use App\Rules\HasPaymentMethods;
use App\Rules\HasShippingMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class CheckoutDetailsRequest extends FormRequest
{
    public function rules(Order $order): array
    {
        return [
            // Shipping address
            'shipping_id'           => Auth::guest() ? ['prohibited', 'nullable'] : ['nullable', 'integer', $this->shippingAddressExists()],
            'shipping_first_name'   => ['required_without:shipping_id', 'string'],
            'shipping_last_name'    => ['required_without:shipping_id', 'string'],
            'shipping_phone'        => ['required_without:shipping_id', 'string'],
            'shipping_country_id'   => ['required_without:shipping_id', 'integer', 'exists:countries,id', new HasShippingMethods($order->products_value), new HasPaymentMethods($order->products_value)],
            'shipping_province'     => ['required_without:shipping_id', 'string'],
            'shipping_city'         => ['required_without:shipping_id', 'string'],
            'shipping_street'       => ['required_without:shipping_id', 'string'],
            'shipping_street_no'    => ['nullable', 'string'],
            'shipping_postcode'     => ['required_without:shipping_id', 'string'],

            // Invoicing
            'invoicing'             => ['sometimes'],
            'company_name'          => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],
            'company_job'           => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],
            'company_vat_number'    => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string', 'max:20'],
            'company_tax_authority' => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],
            'company_phone'         => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],
            'company_country_id'    => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'integer', 'exists:countries,id'],
            'company_province'      => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],
            'company_street'        => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],
            'company_street_no'     => ['prohibited_unless:invoicing,on', 'nullable', 'string'],
            'company_city'          => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],
            'company_postcode'      => ['required_with:invoicing', 'prohibited_unless:invoicing,on', 'string'],

            // Customer notes
            'email'                 => [Auth::check() ? 'nullable' : 'required', 'email:rfc,dns'],
            'details'               => ['nullable', 'string', 'max:255']
        ];
    }

    /**
     * Make sure shipping address exists and it belongs to the same user
     */
    private function shippingAddressExists(): ?Exists
    {
        return Rule::exists('addresses', 'id')
            ->where('addressable_id', user()->id)
            ->where('addressable_type', 'user');
    }
}
