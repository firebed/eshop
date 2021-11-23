<?php

namespace Eshop\Actions\Analytics;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OrderAnalytics
{
    public function totalOrders(Carbon|null $from = null, Carbon|null $to = null, string $interval = '1 day'): Collection
    {
        return (new TotalOrders())->handle($from, $to, $interval);
    }

    public function averageOrders(Carbon|null $from = null, Carbon|null $to = null, string $interval = '1 day'): Collection
    {
        return (new AverageOrders())->handle($from, $to, $interval);        
    }

    public function totalIncome(Carbon|null $from, Carbon|null $to, string $interval = '1 day'): Collection
    {
        return (new TotalIncome())->handle($from, $to, $interval);
    }
    
    public function orderStatuses(Carbon $from, Carbon $to): Collection
    {
        return (new OrderStatuses())->handle($from, $to);
    }

    public function profits(Carbon|null $from, Carbon|null $to, string $interval = '1 day'): Collection
    {
        return (new ProfitAnalytics())->handle($from, $to, $interval);
    }
}
