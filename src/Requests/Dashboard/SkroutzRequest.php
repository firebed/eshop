<?php

namespace Eshop\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\IpUtils;

class SkroutzRequest extends FormRequest
{
    private const IP_RANGE = "185.6.76.0/22";

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return app()->isLocal() || IpUtils::checkIp($this->ip(), self::IP_RANGE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [

        ];
    }
}
