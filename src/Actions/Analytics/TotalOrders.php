<?php

namespace Eshop\Actions\Analytics;

use Carbon\CarbonPeriod;
use Eshop\Actions\Analytics\Traits\CartsQuery;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TotalOrders
{
    use CartsQuery;

    public function handle(Carbon|null $from, Carbon|null $to, string $interval = '1 day'): Collection
    {
        $group = str_contains($interval, ' ')
            ? substr($interval, strpos($interval, ' ') + 1)
            : $interval;

        $data = $this->carts($from, $to)
            ->select(array_merge($this->getSelectStatement($group), [DB::raw("COUNT(*) as `count`")]))
            ->groupBy('grp')
            ->orderBy('submitted_at')
            ->get()
            ->pluck('count', 'grp');

        if ($group === 'weekday') {
            $period = CarbonPeriod::create(now()->startOfWeek(), '1 day', now()->endOfWeek());
            $d = collect();
            foreach ($period as $date) {
                if (!$data->has($date->dayOfWeek)) {
                    $d->put($date->isoFormat('ddd'), 0);
                } else {
                    $d->put($date->isoFormat('ddd'), $data[$date->dayOfWeek]);
                }
            }
            
            return $d;
        }

        if ($from === null) {
            $from = now()->setYear($data->keys()->first())->startOfYear();
            $to = now()->endOfDay();
        }

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
            'weekday' => [DB::raw('WEEKDAY(`submitted_at`) as `grp`')],
            "day" => [DB::raw("DATE(`submitted_at`) as `grp`")],
            "month" => [DB::raw("DATE_FORMAT(`submitted_at`, '%Y-%m') as `grp`")],
            "year" => [DB::raw("YEAR(`submitted_at`) as `grp`")]
        };
    }
}
