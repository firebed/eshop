<?php

namespace Eshop\Services\SpeedEx\Http;

use Carbon\Carbon;
use Eshop\Services\SpeedEx\Exceptions\SpeedExException;

class SpeedExGetPayouts extends SpeedExRequestV2
{
    protected string $action = 'GetDepositedConsignmentsByDate';

    /**
     * @throws SpeedExException
     */
    public function handle(Carbon $date): array
    {
        $response = $this->request([
            'dateFrom' => $date->startOfDay()->toAtomString(),
            'dateTo'   => $date->copy()->endOfDay()->toAtomString()
        ]);

        return $response->ConsignmentCollectOnDelivery ?? [];
    }
}