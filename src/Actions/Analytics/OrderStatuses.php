<?php

namespace Eshop\Actions\Analytics;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderStatuses
{
    public function handle(Carbon $from, Carbon $to): Collection
    {
        return DB::table('carts')
            ->select('cart_statuses.name', DB::raw("COUNT(*) as `count`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$from, $to])
            ->groupBy('cart_statuses.name')
            ->get()
            ->pluck('count', 'name');
    }
}
