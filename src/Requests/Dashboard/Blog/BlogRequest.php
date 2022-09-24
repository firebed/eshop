<?php

namespace Eshop\Requests\Dashboard\Blog;

use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    use WithRequestNotifications;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'     => ['required', 'integer', 'exists:users,id'],
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'regex:/^(?!-)(?!.*--)[a-z0-9-]+(?<!-)$/', 'max:255'],
            'content'     => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'image'       => ['nullable', 'image']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id'     => auth()->id(),
            'description' => (string)str($this->input('description'))
                ->replaceMatches("!\s\s+!", " ")
        ]);
    }
}
