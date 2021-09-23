<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Location\Country;

class PaymentFeeCalculator
{
    public function handle(Country $country, float $productsTotal, $preferredCountryPaymentMethodId = null): null|array
    {
        $paymentOptions = $country->filterPaymentOptions($productsTotal);
        if ($paymentOptions->isEmpty()) {
            return null;
        }

        $method = null;

        if ($preferredCountryPaymentMethodId !== null) {
            $method = $paymentOptions->firstWhere('id', $preferredCountryPaymentMethodId);
        }

        if ($method === null) {
            $method = $paymentOptions->first();
        }

        return [$method, $method->fee];
    }
}