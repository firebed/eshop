<?php

namespace Eshop\Services\SpeedEx\Http;

use Carbon\Carbon;
use Eshop\Services\SpeedEx\Exceptions\SpeedExException;
use Illuminate\Support\Collection;

class SpeedExGetVouchersByDate extends SpeedExRequestV2
{
    protected string $action = 'GetConsignmentsByDate';

    /**
     * @throws SpeedExException
     */
    public function handle(Carbon $date): Collection
    {
        $results = $this->request([
            'dateFrom' => $date->startOfDay()->toAtomString(),
            'dateTo'   => $date->endOfDay()->toAtomString()
        ]);
        
        return collect($results);
    }
}