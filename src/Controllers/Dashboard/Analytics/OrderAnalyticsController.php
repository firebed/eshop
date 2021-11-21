<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Eshop\Actions\Analytics\OrderAnalytics;
use Eshop\Controllers\Dashboard\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class OrderAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:View analytics');
    }

    public function __invoke(Request $request, OrderAnalytics $analytics): Renderable
    {
        return $this->view('analytics.orders.index', [
            'orders' => $analytics->totalOrders(now()->startOfMonth(), now()->endOfDay()),
            'weekday_orders' => $analytics->totalOrders(interval: 'weekday'),
            'statuses' => $analytics->orderStatuses(now()->startOfMonth(), now()->endOfDay()),
            'monthly_orders' => $analytics->totalOrders(now()->startOfYear(), now()->endOfYear(), '1 month'),
            'yearly_orders' => $analytics->totalOrders(null, null, '1 year'),
            'monthly_income' => $analytics->totalIncome(now()->startOfYear(), now()->endOfYear(), '1 month'),
            'yearly_income' => $analytics->totalIncome(null, null, '1 year'),
            'monthly_profits' => $analytics->profits(now()->startOfYear(), now()->endOfYear(), '1 month'),
            'yearly_profits' => $analytics->profits(null, null, '1 year'),
        ]);
    }
}
