<?php

namespace Eshop\Services\CourierCenter\Http;

use Carbon\Carbon;
use Eshop\Services\CourierCenter\Exceptions\CourierCenterException;

class CourierCenterPickupList extends CourierCenterRequest
{
    protected string $action = 'Manifest';

    /**
     * @throws CourierCenterException
     */
    public function handle(Carbon $date): bool|string
    {
        $response = $this->request(array_filter([
            'Date' => $date->format('Y-m-d')
        ]));

        return base64_decode($response['Manifest']);
    }
}