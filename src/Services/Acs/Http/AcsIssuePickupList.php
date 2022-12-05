<?php

namespace Eshop\Services\Acs\Http;


use Carbon\Carbon;

class AcsIssuePickupList extends AcsRequest
{
    protected string $action = 'ACS_Issue_Pickup_List';

    /**
     * @param Carbon $pickup_date
     * @param string $locale
     * @return array|int
     */
    public function handle(Carbon $pickup_date, string $locale = 'GR'): array|int
    {
        return $this->request([
            'Language'    => $locale,
            'Pickup_Date' => $pickup_date->format('Y-m-d'),
        ]);
    }
}