<?php

namespace Eshop\Services\SpeedEx\Http;

use Carbon\Carbon;

class SpeedExGetVouchersByDate extends SpeedExRequest
{
    protected string $action = 'GetVouchersByDate';

    public function handle(Carbon $date)
    {
        return $this->request([
            'dateFrom' => $date->startOfDay()->toAtomString(),
            'dateTo'   => $date->endOfDay()->toAtomString()
        ]);
    }
}