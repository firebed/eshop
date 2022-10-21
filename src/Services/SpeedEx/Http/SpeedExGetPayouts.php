<?php

namespace Eshop\Services\SpeedEx\Http;

use Carbon\Carbon;

class SpeedExGetPayouts extends SpeedExRequest
{
    protected string $action = 'GetDepositedConsignmentsByDate';

    public function handle(Carbon $date): array
    {
        $response = $this->request([
            'dateFrom' => $date->startOfDay()->toAtomString(),
            'dateTo'   => $date->copy()->endOfDay()->toAtomString()
        ]);

        if ($response === null) {
            return [];
        }

        $result = $response->GetDepositedConsignmentsByDateResult;
        if ($result === null) {
            return [];
        }

        $message = $result->Message;
        $statusCode = $result->StatusCode;

        $consignments = $result->Result->ConsignmentCollectOnDelivery ?? [];
        return is_array($consignments) ? $consignments : [];
    }
}