<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Location\Country;
use Eshop\Models\Location\CountryShippingMethod;

class FindShippingMethodForOrder
{
    public function handle(Country $country, float $productsValue, $preferredCountryShippingMethodId = null): CountryShippingMethod|null
    {
        $shippingOptions = $country->filterShippingOptions($productsValue);
        if ($shippingOptions->isEmpty()) {
            return null;
        }

        $method = null;

        if ($preferredCountryShippingMethodId) {
            $method = $shippingOptions->firstWhere('id', $preferredCountryShippingMethodId);
        }

        return $method ?? $shippingOptions->first();
    }
}