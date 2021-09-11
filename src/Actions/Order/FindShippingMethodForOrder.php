<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Location\Country;
use Eshop\Models\Location\CountryShippingMethod;

class FindShippingMethodForOrder
{
    public function handle(Country $country, float $productsValue, $preferredCountryShippingMethodId = NULL): CountryShippingMethod|null
    {
        $shippingOptions = $country->filterShippingOptions($productsValue);
        if ($shippingOptions->isEmpty()) {
            return NULL;
        }

        $preferredShippingMethod = $shippingOptions->firstWhere('id', $preferredCountryShippingMethodId);
        if ($preferredShippingMethod) {
            return $preferredShippingMethod;
        }

        return $shippingOptions->first();
    }
}