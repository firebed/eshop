<?php

namespace Eshop\Services\CourierCenter\Http;

use Illuminate\Support\Collection;

class CourierCenterTracking extends CourierCenterRequest
{
    protected string $action = 'Tracking';

    public function handle(string $voucher): Collection
    {
        $checkpoints = $this->request([
            'Identifier' => $voucher
        ]);

        return collect($checkpoints["TrackingList"] ?? []);
    }
}