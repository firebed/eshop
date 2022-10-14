<?php

namespace Eshop\Services\Acs\Imports;

use Eshop\Services\Concerns\PayoutsImport;

class ACSImport extends PayoutsImport
{
    protected function voucherColumn(): int
    {
        return 2;
    }

    protected function totalColumn(): int
    {
        return 3;
    }
}