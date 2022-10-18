<?php

namespace Eshop\Services\SpeedEx\Http;

use Illuminate\Support\Collection;

class SpeedExGetTraceByVoucher extends SpeedExRequest
{
    protected string $action = 'GetTraceByVoucher';

    public function handle(string $voucher): Collection
    {
        $response = $this->request([
            'VoucherID' => $voucher,
        ]);
        
        if ($response === null) {
            return collect();
        }
        
        return collect($response->checkpoints->Checkpoint ?? [])->map(fn($checkpoint) => (array) $checkpoint);
    }
}