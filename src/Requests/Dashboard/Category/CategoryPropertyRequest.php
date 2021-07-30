<?php

namespace Eshop\Requests\Dashboard\Category;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;

class CategoryPropertyRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'visible' => $this->has('visible'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string'],
            'slug'           => ['required', 'string'],
            'type'           => ['required', 'in:radio,checkbox'],

            # Choices
            'choices'        => ['nullable', 'array'],
            'choices.*.id'   => ['nullable', 'integer'],
            'choices.*.name' => ['required', 'string'],
        ];
    }
}
