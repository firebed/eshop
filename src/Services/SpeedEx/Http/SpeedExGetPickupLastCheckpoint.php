<?php

namespace Eshop\Services\SpeedEx\Http;

class SpeedExGetPickupLastCheckpoint extends SpeedExRequest
{
    protected string $action = 'GetOrderLastCheckpoint';

    public function handle(string $pickupId)
    {
        return $this->request([
            'orderid' => $pickupId
        ]);
    }
}