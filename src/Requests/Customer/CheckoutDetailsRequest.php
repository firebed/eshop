<?php

namespace Eshop\Requests\Customer;

use Eshop\Models\Location\Country;
use Eshop\Rules\PhoneNumber;
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
            $country = Country::find($this->input('shippingAddress.country_id'));

            $rules = array_merge($rules, [
                'shippingAddress'            => ['required', 'array'],
                'shippingAddress.first_name' => ['required', 'string'],
                'shippingAddress.last_name'  => ['required', 'string'],
                'shippingAddress.phone'      => ['required', 'string', new PhoneNumber($this->input('shippingAddress.country_id'))],
                'shippingAddress.country_id' => ['required', 'integer', 'exists:countries,id'],
                'shippingAddress.province'   => ['required', 'string', Rule::when(fn() => $country?->provinces()->exists(), ['exists:provinces,name'])],
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
                'invoiceAddress.phone'       => ['required', 'nullable', 'string', new PhoneNumber($this->input('invoiceAddress.country_id'))],
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

    protected function prepareForValidation()
    {
        if (eshop('validate_phone_number', false)) {
            $shippingAddress = $this->input('shippingAddress', []);
            $shippingAddress['phone'] = preg_replace('/[^0-9]/', '', $this->input('shippingAddress.phone'));
            $this->merge(['shippingAddress' => $shippingAddress]);

            if ($this->filled('invoicing')) {
                $invoiceAddress = $this->input('invoiceAddress', []);
                $invoiceAddress['phone'] = preg_replace('/[^0-9]/', '', $this->input('invoiceAddress.phone'));
                $this->merge(['invoiceAddress' => $invoiceAddress]);
            }
        }
    }

    public function attributes(): array
    {
        return [
            'shippingAddress.first_name' => trans('validation.attributes.first_name'),
            'shippingAddress.last_name' => trans('validation.attributes.last_name'),
            'shippingAddress.phone' => trans('validation.attributes.phone'),
            'shippingAddress.country_id' => trans('validation.attributes.country_id'),
            'shippingAddress.province' => trans('validation.attributes.province'),
            'shippingAddress.city' => trans('validation.attributes.city'),
            'shippingAddress.street' => trans('validation.attributes.street'),
            'shippingAddress.street_no' => trans('validation.attributes.street_no'),
            'shippingAddress.postcode' => trans('validation.attributes.postcode'),

            'invoice.name'               => trans('validation.attributes.company_name'),
            'invoice.job'                => trans('validation.attributes.job'),
            'invoice.vat_number'         => trans('validation.attributes.vat_number'),
            'invoice.tax_authority'      => trans('validation.attributes.tax_authority'),
            'invoiceAddress.phone'       => trans('validation.attributes.phone'),
            'invoiceAddress.country_id'  => trans('validation.attributes.country_id'),
            'invoiceAddress.province'    => trans('validation.attributes.province'),
            'invoiceAddress.street'      => trans('validation.attributes.street'),
            'invoiceAddress.street_no'   => trans('validation.attributes.street_no'),
            'invoiceAddress.city'        => trans('validation.attributes.city'),
            'invoiceAddress.postcode'    => trans('validation.attributes.postcode'),
        ];
    }
}
