<?php

namespace Eshop\Requests\Dashboard\Intl;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Rule;

class ProvinceRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $province = $this->route('province');
        $country = $this->route('country');

        if ($country === null) {
            $province = $this->route('province');
            $country = $province->country;
        }

        return [
            'name'       => ['required', 'string', Rule::unique('provinces')->where('country_id', $country->id)->when($province, fn($q) => $q->ignore($province))],
            'shippable'  => ['required', 'boolean']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'shippable' => $this->has('shippable'),
        ]);
    }
}
