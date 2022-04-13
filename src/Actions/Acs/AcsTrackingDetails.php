<?php

namespace Eshop\Actions\Acs;

class AcsTrackingDetails extends AcsRequest
{
    protected string $action = 'ACS_TrackingDetails';

    public function handle(string $voucher = '4996400533'): int|array
    {
        return $this->request(["Voucher_No" => $voucher]);
    }
}