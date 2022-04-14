<?php

namespace Eshop\Actions\SpeedEx;

use Carbon\Carbon;

class SpeedExPaymentsInfo extends SpeedExRequest
{
    protected string $action = 'GetDepositedConsignmentsByDate';

    public function handle(Carbon $fromDatetime, Carbon $toDatetime): array
    {
        $response = $this->request([
            'dateFrom' => $fromDatetime->toAtomString(),
            'dateTo'   => $toDatetime->toAtomString()
        ]);

        $result = $response->GetDepositedConsignmentsByDateResult;

        $message = $result->Message;
        $statusCode = $result->StatusCode;
        
        $consignments = $result->Result->ConsignmentCollectOnDelivery ?? [];
        return is_array($consignments) ? $consignments : [];
    }
}