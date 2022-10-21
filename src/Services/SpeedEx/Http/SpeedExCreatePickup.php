<?php

namespace Eshop\Services\SpeedEx\Http;

use Carbon\Carbon;
use stdClass;
use Throwable;

class SpeedExCreatePickup extends SpeedExRequest
{
    protected string $action = 'CreatePickup';

    /**
     * @param array       $vouchers One or more master consignment numbers. The maximum length is 5 master consignment numbers.
     * @param Carbon      $pickupDate
     * @param string|null $pickupTimeFrom The start of the requested time frame of the pickup. The value must be “10:00”, “13:00” or “16:00”.
     * @param string|null $pickupTimeTo The end of the requested time frame of the pickup. The value must be “13:00”, “16:00” or “19:00”.
     * @param string|null $pickupCustomerComments
     * @return stdClass|null
     * @throws Throwable
     */
    public function handle(array $vouchers, Carbon $pickupDate, string $pickupTimeFrom = null, string $pickupTimeTo = null, string $pickupCustomerComments = null)
    {
        return $this->request(array_filter([
            'consignmentNumbers'     => $vouchers,
            'pickupDate'             => $pickupDate->toAtomString(),
            'pickupTimeFrom'         => $pickupTimeFrom,
            'pickupTimeTo'           => $pickupTimeTo,
            'pickupCustomerComments' => $pickupCustomerComments
        ]));
    }
}