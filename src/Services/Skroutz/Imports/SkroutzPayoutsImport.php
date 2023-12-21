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

        if ($orderId === 'Σύνολο') {
            return [];
        }

        $customer = $row[1];
        $total = parseFloat($row[4]);
        $total_payout = parseFloat($row[11]);
        $fees = round($total - $total_payout, 2);

        return [
            $orderId => [
                'customer_name' => $customer,
                'fees'          => $fees,
                'total'         => $total_payout
            ]
        ];
    }
}
