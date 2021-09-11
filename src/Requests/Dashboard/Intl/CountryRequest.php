<?php

namespace Eshop\Requests\Dashboard\Intl;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $country = $this->route('country');

        return [
            'name'     => ['required', 'string', Rule::unique('countries')->when($country, fn($q) => $q->ignore($country))],
            'code'     => ['required', 'string', 'regex:/^[A-Z]{2}$/', Rule::unique('countries')->when($country, fn($q) => $q->ignore($country))],
            'timezone' => ['nullable', 'string'],
            'visible'  => ['required', 'boolean']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'visible' => $this->has('visible'),
        ]);
    }
}
