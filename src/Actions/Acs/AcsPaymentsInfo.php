<?php

namespace Eshop\Actions\Acs;

use Carbon\Carbon;

class AcsPaymentsInfo extends AcsRequest
{
    protected string $action = 'ACS_COD_Beneficiary_Info';

    public function handle(Carbon $date)
    {
        $params = ["COD_Payment_Date" => $date->format('Y-m-d')];

        [$value, $table] = $this->request($params);
        
        return $table;
    }
}