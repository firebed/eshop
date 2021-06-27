<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Eshop\Controllers\Controller;
use Eshop\Models\Cart\CartChannel;
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
            'ordersChannelToday'     => $this->ordersChannel(today()),
            'ordersChannelYesterday' => $this->ordersChannel(Carbon::yesterday()),
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

    private function ordersChannel(Carbon $date): Collection
    {
        $data = DB::table('carts')
            ->select('channel', DB::raw("COUNT(*) as `total`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$date->startOfDay(), $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::calculable())
            ->groupBy('channel')
            ->get()
            ->pluck('total', 'channel');

        foreach (CartChannel::all() as $channel) {
            if (!isset($data[$channel])) {
                $data[$channel] = 0;
            }
        }


        return $data->mapWithKeys(fn($v, $k) => [__("eshop::cart.channel.$k") => $v])->sortKeys();
    }
}
