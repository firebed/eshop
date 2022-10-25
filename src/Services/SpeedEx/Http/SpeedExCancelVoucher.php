<?php

namespace Eshop\Services\SpeedEx\Http;

class SpeedExCancelVoucher extends SpeedExRequest
{
    protected string $action = 'CancelBOL';
    
    public function handle(string $voucher): bool|string
    {
        $response = $this->request([
            'voucherID' => $voucher
        ]);
        
        if ($response->returnCode === 1) {
            return true;
        }
        
        return $response->returnMessage;
    }
}