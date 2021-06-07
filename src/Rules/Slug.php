<?php


namespace Eshop\Rules;


use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    public function passes($attribute, $value)
    {

        return 'regex:/^([a-z0-9]+-)*[a-z0-9]+$/i';
    }

    public function message()
    {
        // TODO: Implement message() method.
    }
}