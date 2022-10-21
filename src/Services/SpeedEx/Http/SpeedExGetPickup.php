<?php

namespace Eshop\Services\SpeedEx\Http;

class SpeedExGetPickup extends SpeedExRequest
{
    protected string $action = 'GetPickup';

    public function handle(string $pickupNumber)
    {
        return $this->request([
            'pickupNumber' => $pickupNumber
        ]);
    }
}