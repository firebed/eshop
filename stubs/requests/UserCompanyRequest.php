<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCompanyRequest extends FormRequest
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
            'name'          => ['required', 'string'],
            'job'           => ['nullable', 'string'],
            'vat_number'    => ['required', 'string'],
            'tax_authority' => ['nullable', 'string'],
            'street'        => ['required', 'string'],
            'street_no'     => ['nullable', 'string'],
            'city'          => ['required', 'string'],
            'postcode'      => ['required', 'string'],
            'province'      => ['required', 'string'],
            'country_id'    => ['required', 'integer', 'exists:countries,id'],
            'floor'         => ['nullable', 'string'],
            'phone'         => ['nullable', 'string'],
        ];
    }
}
