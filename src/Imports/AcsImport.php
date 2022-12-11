<?php

namespace Eshop\Imports;


class AcsImport extends PayoutsImport
{
    public function map($row): array
    {
        $voucher = $row[2] ?? null;
        $customer = $row[20] ?? null;
        $total = $row[3] ?? null;

        if ($voucher == null || $total === null) {
            return [];
        }

        return [
            $voucher => [
                'customer_name' => $customer,
                'fees'          => 0,
                'total'         => parseFloat($total)
            ]
        ];
    }
}