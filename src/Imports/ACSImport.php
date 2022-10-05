<?php

namespace Eshop\Imports;

class ACSImport extends VoucherImport
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