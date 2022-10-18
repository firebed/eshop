<?php

namespace Eshop\Services\Acs\Http;

use Illuminate\Support\Collection;

class AcsTrackingDetails extends AcsRequest
{
    protected string $action = 'ACS_TrackingDetails';

    public function handle(string $voucher): Collection
    {
        [$_, $table] = $this->request(["Voucher_No" => $voucher]);

        return collect($table);
    }
}