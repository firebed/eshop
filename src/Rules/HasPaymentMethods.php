<?php

namespace Eshop\Rules;

use Eshop\Models\Location\Country;
use Illuminate\Contracts\Validation\Rule;

class HasPaymentMethods implements Rule
{
    private float $productsValue;

    public function __construct(float $productsValue)
    {
        $this->productsValue = $productsValue ?: 0;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $country = Country::find($value);
        return $country && $country->filterPaymentOptions($this->productsValue)->isNotEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('order.empty_payment_methods');
    }
}
