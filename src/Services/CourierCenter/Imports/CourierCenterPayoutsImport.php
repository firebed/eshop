<?php

namespace Eshop\Services\CourierCenter\Imports;

use Eshop\Services\Payout\PayoutsImport;

class CourierCenterPayoutsImport extends PayoutsImport
{
    public function map($row): array
    {
        $voucher = $row[11] ?? null;
        $customer = $row[20] ?? null;
        $total = $row[21] ?? null;

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