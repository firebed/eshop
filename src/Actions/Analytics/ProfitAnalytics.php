<?php

namespace Eshop\Actions\Analytics;

use Carbon\CarbonPeriod;
use Eshop\Actions\Analytics\Traits\CartsQuery;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProfitAnalytics
{
    use CartsQuery;

    public function handle(Carbon|null $from, Carbon|null $to, string $interval = '1 day'): Collection
    {
        $group = substr($interval, strpos($interval, ' ') + 1);

        $data = $this->carts($from, $to)
            ->select(array_merge($this->getSelectStatement($group), [DB::raw("ROUND(SUM(`quantity` * (`price`/(1+`vat`) * (1 - `discount`) - `compare_price`)) - SUM(`fees`), 2) as `profits`")]))
            ->leftJoin('cart_product', 'cart_product.cart_id', '=', 'carts.id')
            ->leftJoin('payments', 'payments.cart_id', '=', 'carts.id')
            ->whereNull('cart_product.deleted_at')
            ->groupBy('grp')
            ->when($from !== null, fn($q) => $q->orderBy('submitted_at'))
            ->where('compare_price', '>', 0)
            ->get()
            ->pluck('profits', 'grp');

        $period = CarbonPeriod::create($from ?? now()->setYear($data->keys()->first())->startOfYear(), $interval, $to ?? now()->endOfDay());

        if ($group === 'month') {
            foreach ($period as $date) {
                if (!$data->has($date->format('Y-m'))) {
                    $data[$date->isoFormat('MMM Y')] = 0;
                } else {
                    $count = $data->get($date->format('Y-m'));
                    $data->forget($date->format('Y-m'));
                    $data->put($date->isoFormat('MMM Y'), $count);
                }
            }
        } elseif ($group === 'year') {
            foreach ($period as $date) {
                if (!$data->has($date->year)) {
                    $data[$date->year] = 0;
                }
            }
        } else {
            foreach ($period as $date) {
                if (!$data->has($date->format('Y-m-d'))) {
                    $data[$date->isoFormat('D MMM')] = 0;
                } else {
                    $count = $data->get($date->format('Y-m-d'));
                    $data->forget($date->format('Y-m-d'));
                    $data->put($date->isoFormat('D MMM'), $count);
                }
            }
        }

        return $data;
    }

    private function getSelectStatement($group): array
    {
        return match ($group) {
            "day" => [DB::raw("DATE(`submitted_at`) as `grp`")],
            "month" => [DB::raw("DATE_FORMAT(`submitted_at`, '%Y-%m') as `grp`")],
            "year" => [DB::raw("YEAR(`submitted_at`) as `grp`")]
        };
    }
}
