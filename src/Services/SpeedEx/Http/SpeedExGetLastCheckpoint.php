<?php

namespace Eshop\Services\SpeedEx\Http;

class SpeedExGetLastCheckpoint extends SpeedExRequest
{
    protected string $action = 'GetLastCheckpoint';

    public function handle(string $voucher)
    {
        return $this->request([
            'voucherID' => $voucher
        ]);
    }
}