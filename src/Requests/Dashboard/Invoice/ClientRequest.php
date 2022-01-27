<?php

namespace Eshop\Requests\Dashboard\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string'],
            'vat_number'    => ['required', 'string', 'unique:clients,vat_number'],
            'tax_authority' => ['nullable', 'string'],
            'job'           => ['required', 'string'],
            'country'       => ['required', 'string', 'regex:/^[A-Z]{2}$/'],
            'city'          => ['required', 'string'],
            'street'        => ['required', 'string'],
            'street_number' => ['nullable', 'string'],
            'postcode'      => ['required', 'string'],
            'phone_number'  => ['required', 'string'],

            'redirect_to' => ['nullable', 'string']
        ];
    }
}