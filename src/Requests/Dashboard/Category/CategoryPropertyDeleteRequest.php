<?php

namespace Eshop\Requests\Dashboard\Category;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\SeoTitle;
use Eshop\Rules\Slug;
use Eshop\Rules\UniqueSlug;
use Eshop\Rules\UniqueTranslation;
use Illuminate\Foundation\Http\FormRequest;

class CategoryPropertyDeleteRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        $property = $this->route('property');

        return [
            'delete_name' => ['required', 'string', 'in:' . $property->name],
        ];
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), ['delete_name' => 'name']);
    }
}
