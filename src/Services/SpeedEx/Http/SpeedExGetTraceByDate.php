<?php

namespace Eshop\Services\SpeedEx\Http;

use Carbon\Carbon;

class SpeedExGetTraceByDate extends SpeedExRequest
{
    protected string $action = 'GetTraceByDate';

    public function handle(Carbon $dateFrom, Carbon $dateTo)
    {
        return $this->request([
            'dateFrom' => $dateFrom->toAtomString(),
            'dateTo'   => $dateTo->toAtomString(),
        ]);
    }
}