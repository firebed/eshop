<?php

namespace Eshop\Services\CourierCenter\Http;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class CourierCenterTracking extends CourierCenterRequest
{
    protected string $action = 'Tracking';

    public function handle(string $voucher): Collection
    {
        $checkpoints = $this->request([
            'Identifier' => $voucher
        ]);

        return collect($checkpoints["TrackingList"] ?? [])
            ->sortByDesc('ExecutedOn')
            ->map(function ($checkpoint) {
                $city = $checkpoint['StationName'];
                $date = Carbon::parse($checkpoint['ExecutedOn']);
                return [
                    'title'       => str($checkpoint['Note']),
                    'description' => $city . ', ' . $date->format('d/m/Y στις H:i')
                ];
            });
    }
}