<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Location\CountryShippingMethod;
use Eshop\Models\Location\InaccessibleArea;

class ShippingFeeCalculator
{
    private ?CountryShippingMethod $method                = null;
    private ?InaccessibleArea      $area                  = null;
    private float                  $base_fee              = 0;
    private float                  $inaccessible_area_fee = 0;
    private float                  $excess_weight_fee     = 0;
    private float                  $total_fee             = 0;

    public function handle(CountryShippingMethod $countryShippingMethod, ?int $weight, ?string $postcode): float
    {
        $this->method = $countryShippingMethod;
        $this->base_fee = $this->method->fee;
        $this->inaccessible_area_fee = $this->calculateInaccessibleAreaFee($countryShippingMethod, $postcode);
        $this->excess_weight_fee = $this->calculateExcessWeightFee($countryShippingMethod, $weight);

        return $this->total_fee = $countryShippingMethod->fee + $this->inaccessible_area_fee + $this->excess_weight_fee;
    }

    public function getMethod(): ?CountryShippingMethod
    {
        return $this->method;
    }

    public function getInaccessibleAreaFee(): float
    {
        return $this->inaccessible_area_fee;
    }

    public function getExcessWeightFee(): float
    {
        return $this->excess_weight_fee;
    }

    public function getBaseFee(): float|int
    {
        return $this->base_fee;
    }

    public function getTotalFee(): float|int
    {
        return $this->total_fee;
    }

    public function getArea(): ?InaccessibleArea
    {
        return $this->area;
    }

    private function calculateExcessWeightFee(CountryShippingMethod $method, ?int $weight): float
    {
        if ($weight === null) {
            return 0;
        }

        return $weight > $method->weight_limit
            ? ceil(($weight - $method->weight_limit) / 1000) * $method->weight_excess_fee
            : 0;
    }

    private function calculateInaccessibleAreaFee(CountryShippingMethod $method, ?string $postcode): float
    {
        if ($method->inaccessible_area_fee === .0 || blank($postcode)) {
            return .0;
        }

        # Check if the postcode belongs to an inaccessible area
        $method->loadMissing('shippingMethod');
        $courier = $method->shippingMethod;
        $this->area = $courier?->inaccessibleAreas()->firstWhere('postcode', $postcode);
        if ($this->area?->type === 'ΔΠ') {
            return $method->inaccessible_area_fee;
        }

        return .0;
    }
}