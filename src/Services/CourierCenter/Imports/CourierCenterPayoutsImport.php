<?php

namespace Eshop\Services\CourierCenter\Imports;

use Eshop\Services\Concerns\PayoutsImport;

class CourierCenterPayoutsImport extends PayoutsImport
{
    protected function voucherColumn(): int
    {
        return 11;
    }

    protected function totalColumn(): int
    {
        return 21;
    }
}