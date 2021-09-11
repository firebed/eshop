<?php

namespace Eshop\Requests\Customer;

use Eshop\Models\Location\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CheckoutDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'selected_shipping_id'       => ['nullable', 'integer', 'exists:addresses,id'],
            'email'                      => [Auth::check() ? 'prohibited' : 'required', 'email:rfc,dns'],
            'details'                    => ['nullable', 'string', 'max:255']
        ];

        if ($this->isNotFilled('selected_shipping_id')) {
            $rules = array_merge($rules, [
                'shippingAddress'            => ['required', 'array'],
                'shippingAddress.first_name' => ['required', 'string'],
                'shippingAddress.last_name'  => ['required', 'string'],
                'shippingAddress.phone'      => ['required', 'string'],
                'shippingAddress.country_id' => ['required', 'integer', 'exists:countries,id'],
                'shippingAddress.province'   => ['required', 'string', Rule::when(fn() => Country::find($this->input('shippingAddress.country_id'))?->has('provinces'), ['exists:provinces,name'])],
                'shippingAddress.city'       => ['required', 'string'],
                'shippingAddress.street'     => ['required', 'string'],
                'shippingAddress.street_no'  => ['nullable', 'string'],
                'shippingAddress.postcode'   => ['required', 'string'],
            ]);
        }

        if ($this->filled('invoicing')) {
            $rules = array_merge($rules, [
                'invoice'                    => ['required', 'nullable', 'array'],
                'invoice.name'               => ['required', 'nullable', 'string'],
                'invoice.job'                => ['required', 'nullable', 'string'],
                'invoice.vat_number'         => ['required', 'nullable', 'string', 'max:20'],
                'invoice.tax_authority'      => ['required', 'nullable', 'string'],

                'invoiceAddress'             => ['required', 'nullable', 'array'],
                'invoiceAddress.phone'       => ['required', 'nullable', 'string'],
                'invoiceAddress.country_id'  => ['required', 'nullable', 'integer', 'exists:countries,id'],
                'invoiceAddress.province'    => ['required', 'nullable', 'string'],
                'invoiceAddress.street'      => ['required', 'nullable', 'string'],
                'invoiceAddress.street_no'   => ['nullable', 'string'],
                'invoiceAddress.city'        => ['required', 'nullable', 'string'],
                'invoiceAddress.postcode'    => ['required', 'nullable', 'string'],
            ]);
        }

        return $rules;
    }
}
