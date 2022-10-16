<?php

namespace Eshop\Services\Acs\Http;

class AcsTrackingDetails extends AcsRequest
{
    protected string $action = 'ACS_TrackingDetails';

    public function handle(string $voucher): int|array
    {
        return $this->request(["Voucher_No" => $voucher]);
    }
}