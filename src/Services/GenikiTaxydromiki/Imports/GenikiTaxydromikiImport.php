<?php

namespace Eshop\Services\GenikiTaxydromiki\Imports;

use Eshop\Services\Payout\PayoutsImport;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class GenikiTaxydromikiImport extends PayoutsImport implements WithCustomCsvSettings
{
    protected function skipLastRow(): bool
    {
        return false;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-16',
            'delimiter'      => "\t"
        ];
    }

    public function map($row): array
    {
        $voucher = $row[1] ?? null;
        $customer = $row[4] ?? null;
        $total = $row[5] ?? null;

        if ($voucher == null || $total === null) {
            return [];
        }

        return [
            $voucher => [
                'customer_name' => $customer,
                'fees'          => 0,
                'total'         => parseFloat($total, ',')
            ]
        ];
    }
}