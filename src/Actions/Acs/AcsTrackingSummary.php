<?php

namespace Eshop\Actions\Acs;

class AcsTrackingSummary extends AcsRequest
{
   protected string $action = 'ACS_Trackingsummary';

    public function handle(string $voucher = '4996400533'): int|array
    {
        return $this->request(["Voucher_No" => $voucher]);
    }
}