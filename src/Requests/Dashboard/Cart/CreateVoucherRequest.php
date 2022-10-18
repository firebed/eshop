<?php

namespace Eshop\Requests\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Illuminate\Foundation\Http\FormRequest;

class CreateVoucherRequest extends FormRequest
{
    use WithNotifications;

    public function authorize(): bool
    {
        return TRUE;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
