<?php

namespace Eshop\Services\CourierCenter\Http;

class CourierCenterCancelVoucher extends CourierCenterRequest
{
    protected string $action = 'Shipment/Void';

    public function handle(string $voucher): bool
    {
        $response = $this->request(array_filter([
            'ShipmentNumber' => $voucher,
        ]));

        return $response->Result === "Success";
    }
}