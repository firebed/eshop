<?php

namespace Eshop\Services\Skroutz\Imports;

use Eshop\Imports\PayoutsImport;

class SkroutzPayoutsImport extends PayoutsImport
{
    public function map($row): array
    {
        if (empty($row[0])) {
            return [];
        }

        $orderId = $row[0];
        $customer = $row[1];
        $total = $row[4];
        $fees = $row[5];

        return [
            $orderId => [
                'customer_name' => $customer,
                'fees'          => $fees,
                'total'         => parseFloat($total)
            ]
        ];
    }
}
