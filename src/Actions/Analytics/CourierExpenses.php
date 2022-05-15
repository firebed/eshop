<?php

namespace Eshop\Actions\Analytics;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CourierExpenses
{
    public function handle(Carbon|null $from = null, Carbon|null $to = null): Collection
    {
        return DB::table('carts')
            ->join('shipping_methods', 'shipping_methods.id', '=', 'carts.shipping_method_id')
            ->where('shipping_methods.is_courier', true)
            ->whereNotNull('submitted_at')
            ->whereIn('status_id', [4, 7]) // Shipped + Returned
            ->when($from !== null && $to !== null, fn($q) => $q->whereBetween('submitted_at', [$from, $to]))
            ->when($from == null && $to === null, fn($q) => $q->whereNotNull('submitted_at'))
            ->selectRaw("shipping_methods.name, YEAR(`submitted_at`) as `year`, MONTH(`submitted_at`) as `month`, SUM(`shipping_fee` + `payment_fee`) as `expenses`")
            ->groupBy('shipping_methods.name', 'year', 'month')
            ->orderBy('submitted_at')
            ->get()
            ->groupBy('name');
    }
}
