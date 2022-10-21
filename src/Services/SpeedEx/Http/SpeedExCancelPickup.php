<?php

namespace Eshop\Services\SpeedEx\Http;

class SpeedExCancelPickup extends SpeedExRequest
{
    protected string $action = 'CancelPickup';

    public function handle(string $pickupNumber)
    {
        return $this->request([
            'pickupNumber' => $pickupNumber
        ]);
    }
}