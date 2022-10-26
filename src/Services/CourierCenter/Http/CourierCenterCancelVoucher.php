<?php

namespace Eshop\Services\CourierCenter\Http;

use Eshop\Services\CourierCenter\Exceptions\CourierCenterException;

class CourierCenterCancelVoucher extends CourierCenterRequest
{
    protected string $action = 'Shipment/Void';

    /**
     * @throws CourierCenterException
     */
    public function handle(string $voucher): bool
    {
        $this->request(array_filter([
            'ShipmentNumber' => $voucher,
        ]));

        return true;
    }
}