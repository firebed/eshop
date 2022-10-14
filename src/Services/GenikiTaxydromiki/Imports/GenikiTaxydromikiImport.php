<?php

namespace Eshop\Services\GenikiTaxydromiki\Imports;

use Eshop\Services\Concerns\PayoutsImport;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class GenikiTaxydromikiImport extends PayoutsImport implements WithCustomCsvSettings
{
    protected function voucherColumn(): int
    {
        return 1;
    }

    protected function totalColumn(): int
    {
        return 6;
    }

    protected function skipLastRow(): bool
    {
        return false;
    }

    protected function parseTotal(string $total): float
    {
        $total = str_replace(',', '.', $total);
        return floatval($total);
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-16',
            'delimiter'      => "\t"
        ];
    }
}