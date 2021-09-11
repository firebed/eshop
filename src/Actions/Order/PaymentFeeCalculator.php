<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Location\Country;
use Eshop\Models\Location\CountryPaymentMethod;

class PaymentFeeCalculator
{
    public function handle(Country $country, float $productsTotal, $preferredShippingMethodId = NULL): null|array
    {
        $paymentMethods = $country->filterPaymentOptions($productsTotal);
        $this->method = NULL;

        if ($paymentMethods->isEmpty()) {
            return NULL;
        }

        if ($preferredShippingMethodId !== NULL) {
            $this->method = $paymentMethods->firstWhere('payment_method_id', $preferredShippingMethodId);
        }

        if ($this->method === NULL) {
            $this->method = $paymentMethods->first();
        }

        if ($this->method === NULL) {
            return NULL;
        }

        return [$this->method, $this->method->fee];
    }
}