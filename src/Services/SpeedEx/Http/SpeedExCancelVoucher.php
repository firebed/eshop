<?php

namespace Eshop\Services\SpeedEx\Http;

class SpeedExCancelVoucher extends SpeedExRequest
{
    public function handle(string $voucher)
    {
        return $this->request([
            'voucherID' => $voucher
        ]);
    }
}