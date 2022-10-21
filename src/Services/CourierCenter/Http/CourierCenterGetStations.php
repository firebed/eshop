<?php

namespace Eshop\Services\CourierCenter\Http;

use Illuminate\Support\Collection;

class CourierCenterGetStations extends CourierCenterRequest
{
    protected string $action = 'Station/GetStations';

    public function handle(string $stationId = null): Collection
    {
        $stations = $this->request(array_filter([
            'Identifier' => $stationId
        ]));
        
        return collect($stations["StationDataInfo"] ?? []);
    }
}