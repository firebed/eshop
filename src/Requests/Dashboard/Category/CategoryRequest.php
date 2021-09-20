<?php

namespace Eshop\Requests\Dashboard\Category;

use Eshop\Requests\Traits\WithRequestNotifications;
use Eshop\Rules\SeoTitle;
use Eshop\Rules\Slug;
use Eshop\Rules\UniqueSlug;
use Eshop\Rules\UniqueTranslation;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'promote' => $this->has('promote'),
        ]);
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'parent_id'       => ['nullable', 'integer', 'exists:categories,id'],
            'type'            => ['required', 'string', 'in:File,Folder'],
            'name'            => ['required', 'string'],

            # Accessibility
            'visible'         => ['required', 'boolean'],
            'promote'         => ['required', 'boolean'],

            # Seo
            'slug'            => ['required', 'string', new Slug(), new UniqueSlug('categories', ignore: $category)],
            'seo.locale'      => ['required', 'string', 'size:2', 'exists:locales,name'],
            'seo.title'       => ['required', 'string', 'max:70', new SeoTitle($category ?? 'category')],
            'seo.description' => ['nullable', 'string'],

            # Media
            'image'           => ['nullable', 'image'],
        ];
    }
}
