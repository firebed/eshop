<?php

namespace Eshop\Services\SpeedEx\Http;

use stdClass;
use Throwable;

class SpeedExReschedulePickup extends SpeedExRequest
{
    protected string $action = 'ReschedulePickup';

    /**
     * @param string      $pickupNumber
     * @param             $pickupDate
     * @param string|null $pickupTimeFrom Start of the requested time frame of the pickup. The value must be “10:00”, “13:00” or “16:00”.
     * @param string|null $pickupTimeTo   End of the requested time frame of the pickup. The value must be “13:00”, “16:00” or “19:00”.
     * @param string|null $pickupCustomerComments
     * @return stdClass|null
     * @throws Throwable
     */
    public function handle(string $pickupNumber, $pickupDate, string $pickupTimeFrom = null, string $pickupTimeTo = null, string $pickupCustomerComments = null)
    {
        return $this->request(array_filter([
            'pickupNumber'           => $pickupNumber,
            'pickupDate'             => $pickupDate,
            'pickupTimeFrom'         => $pickupTimeFrom,
            'pickupTimeTo'           => $pickupTimeTo,
            'pickupCustomerComments' => $pickupCustomerComments
        ]));
    }
}