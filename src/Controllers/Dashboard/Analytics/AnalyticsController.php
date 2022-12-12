<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\CartChannel;
use Eshop\Models\Cart\CartStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {        
        $this->middleware('can:View analytics');
    }

    public function __invoke(Request $request): Renderable
    {        
        $date = $request->filled('date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('date'))->startOfDay()
            : today();

        $dateComparison = $request->filled('date_comparison')
            ? Carbon::createFromFormat('Y-m-d', $request->input('date_comparison'))->startOfDay()
            : Carbon::yesterday();

        return $this->view('analytics.index', [
            'totalOrders'               => $this->totalOrders($date),
            'totalOrdersComparison'     => $this->totalOrders($dateComparison),
            'totalSales'                => $this->totalSales($date),
            'totalSalesComparison'      => $this->totalSales($dateComparison),
            'totalProfit'               => $this->totalProfit($date),
            'totalProfitComparison'     => $this->totalProfit($dateComparison),
            'paymentMethods'            => $this->paymentMethods($date),
            'paymentMethodsComparison'  => $this->paymentMethods($dateComparison),
            'shippingMethods'           => $this->shippingMethods($date),
            'shippingMethodsComparison' => $this->shippingMethods($dateComparison),
            'ordersChannel'             => $this->ordersChannel($date),
            'ordersChannelComparison'   => $this->ordersChannel($dateComparison),
            'date'                      => $date,
            'dateComparison'            => $dateComparison,
        ]);
    }

    private function totalOrders(Carbon|false|null $date): Collection
    {
        if (empty($date)) {
            return collect([]);
        }

        $data = DB::table('carts')
            ->select(DB::raw("HOUR(`submitted_at`) as `hour`"), DB::raw("COUNT(*) as `count`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$date, $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::valid())
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

    private function totalSales(Carbon|false|null $date): Collection
    {
        if (empty($date)) {
            return collect([]);
        }

        $data = DB::table('carts')
            ->select(DB::raw("HOUR(`submitted_at`) as `hour`"), DB::raw("SUM(`total`) as `total`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$date, $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::valid())
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

    private function totalProfit(Carbon|false|null $date): Collection
    {
        if (empty($date)) {
            return collect([]);
        }

        $data = DB::table('carts')
            ->select(DB::raw("HOUR(`submitted_at`) as `hour`"), DB::raw("ROUND(SUM(`quantity` * (`price`/(1+`vat`) * (1 - `discount`) - `compare_price`)), 2) as `total`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->join('cart_product', 'cart_product.cart_id', '=', 'carts.id')
            ->whereNull('cart_product.deleted_at')
            ->whereBetween('submitted_at', [$date, $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::valid())
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

    private function paymentMethods(Carbon|false|null $date): Collection
    {
        if (empty($date)) {
            return collect([]);
        }

        return DB::table('carts')
            ->selectRaw("COUNT(*) as `total`, payment_methods.name as payment_method_name")
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'carts.payment_method_id')
            ->whereBetween('submitted_at', [$date, $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::valid())
            ->groupBy('payment_method_name')
            ->get()
            ->pluck('total', 'payment_method_name');
    }

    private function shippingMethods(Carbon|false|null $date): Collection
    {
        if (empty($date)) {
            return collect([]);
        }

        return DB::table('carts')
            ->selectRaw("COUNT(*) as `total`, shipping_methods.name as shipping_method_name")
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->join('shipping_methods', 'shipping_methods.id', '=', 'carts.shipping_method_id')
            ->whereBetween('submitted_at', [$date, $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::valid())
            ->groupBy('shipping_method_name')
            ->get()
            ->pluck('total', 'shipping_method_name');
    }

    private function ordersChannel(Carbon|false|null $date): Collection
    {
        if (empty($date)) {
            return collect([]);
        }

        $data = DB::table('carts')
            ->select('channel', DB::raw("COUNT(*) as `total`"))
            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
            ->whereBetween('submitted_at', [$date, $date->copy()->endOfDay()])
            ->whereIn('cart_statuses.name', CartStatus::valid())
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
