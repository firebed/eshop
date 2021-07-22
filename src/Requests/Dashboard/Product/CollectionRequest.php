<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CollectionRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string']
        ];
    }
}
