<?php

namespace Eshop\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name'  => ['required', 'string'],
            'street'     => ['required', 'string'],
            'street_no'  => ['nullable', 'string'],
            'city'       => ['required', 'string'],
            'postcode'   => ['required', 'string'],
            'province'   => ['required', 'string'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'floor'      => ['nullable', 'string'],
            'phone'      => ['required', 'string'],
        ];
    }
}
