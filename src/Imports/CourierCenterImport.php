<?php

namespace Eshop\Imports;

class CourierCenterImport extends VoucherImport
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