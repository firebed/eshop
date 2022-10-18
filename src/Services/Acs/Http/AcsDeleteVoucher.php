<?php

namespace Eshop\Services\Acs\Http;

use Illuminate\Support\Arr;

class AcsDeleteVoucher extends AcsRequest
{
    protected string $action = 'ACS_Delete_Voucher';

    public function handle(string $vouchers): mixed
    {
        $vouchers = Arr::wrap($vouchers);
        $vouchers = array_map('trim', $vouchers);

        $this->request([
            'Voucher_No' => implode(',', $vouchers),
        ]);

        return null;
    }
}