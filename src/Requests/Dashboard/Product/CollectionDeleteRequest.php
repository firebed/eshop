<?php

namespace Eshop\Requests\Dashboard\Product;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CollectionDeleteRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'ids'   => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:collections,id'],
        ];
    }
}
