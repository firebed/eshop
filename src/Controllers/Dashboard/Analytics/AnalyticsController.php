<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Eshop\Controllers\Controller;
use Eshop\Models\Cart\CartStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __invoke(): Renderable
    {
        return view('eshop::dashboard.analytics.index', [
            'totalOrdersToday'      => $this->totalOrders(today()),
            'totalOrdersYesterday'  => $this->totalOrders(Carbon::yesterday()),
            'totalSalesToday'       => $this->totalSales(today()),
            'totalSalesYesterday'   => $this->totalSales(Carbon::yesterday()),
            'ordersSourceToday'     => $this->ordersSource(today()),
            'ordersSourceYesterday' => $this->ordersSource(Carbon::yesterday()),
        ]);
    }

    private function totalOrders(Carbon $date): Collection
    {
        $data = DB::table('carts')
            ->select(DB::raw("HOUR(`submitted_at`) as `hour`"), DB::raw("COUNT(*) as `count`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$date->startOfDay(), $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::calculable())
            ->groupBy('hour')
            ->get()
            ->pluck('count', 'hour');

        for ($i = 0; $i < 24; $i++) {
            if (!isset($data[$i])) {
                $data[$i] = 0;
            }
        }

        return $data->sortKeys();
    }

    private function totalSales(Carbon $date): Collection
    {
        $data = DB::table('carts')
            ->select(DB::raw("HOUR(`submitted_at`) as `hour`"), DB::raw("SUM(`total`) as `total`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$date->startOfDay(), $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::calculable())
            ->groupBy('hour')
            ->get()
            ->pluck('total', 'hour');

        for ($i = 0; $i < 24; $i++) {
            if (!isset($data[$i])) {
                $data[$i] = 0;
            }
        }

        return $data->sortKeys();
    }

    private function ordersSource(Carbon $date): Collection
    {
        $data = DB::table('carts')
            ->select('source', DB::raw("COUNT(*) as `total`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$date->startOfDay(), $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::calculable())
            ->groupBy('source')
            ->get()
            ->pluck('total', 'source');

        foreach (['Facebook', 'Instagram', 'Online', 'Other', 'Phone', 'Retail'] as $source) {
            if (!isset($data[$source])) {
                $data[$source] = 0;
            }
        }

        return $data->sortKeys();
    }
}
