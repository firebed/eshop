<?php

namespace Eshop\Requests\Dashboard\Category;

use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Rules\CategoryIsNotChildOf;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CategoryMoveRequest extends FormRequest
{
    use WithNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [
            'source_ids'   => ['bail', 'required', 'array'],
            'source_ids.*' => ['required', 'integer', 'exists:categories,id'],
            'target_id'    => ['nullable', 'integer', 'exists:categories,id', new CategoryIsNotChildOf($this->input('source_ids'))]
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $this->showWarningNotification($validator->errors()->first());

        parent::failedValidation($validator);
    }
}
