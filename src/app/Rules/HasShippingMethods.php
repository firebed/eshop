<?php

namespace App\Rules;

use App\Http\Livewire\Cart\ShippingAddress;
use App\Models\Location\Country;
use Illuminate\Contracts\Validation\Rule;

class HasShippingMethods implements Rule
{
    private float $productsValue;

    public function __construct(float $productsValue)
    {
        $this->productsValue = $productsValue ?: 0;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string          $attribute
     * @param ShippingAddress $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $country = Country::find($value);

        return $country && $country->filterShippingOptions($this->productsValue)->isNotEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('order.empty_shipping_methods');
    }
}
