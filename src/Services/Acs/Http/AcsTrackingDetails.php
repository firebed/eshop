<?php

namespace Eshop\Services\Acs\Http;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class AcsTrackingDetails extends AcsRequest
{
    protected string $action = 'ACS_TrackingDetails';

    public function handle(string $voucher): Collection
    {
        [$_, $table] = $this->request(["Voucher_No" => $voucher]);

        return collect($table)
            ->sortByDesc('checkpoint_date_time')
            ->map(function ($checkpoint) {
                $city = $checkpoint['checkpoint_location'];
                $date = Carbon::parse($checkpoint['checkpoint_date_time']);
                return [
                    'title'       => str($checkpoint['checkpoint_action']),
                    'description' => $city . ', ' . $date->format('d/m/Y στις H:i')
                ];
            });
    }
}