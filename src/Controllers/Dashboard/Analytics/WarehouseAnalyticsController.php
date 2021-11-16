<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Eshop\Actions\Analytics\OrderAnalytics;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:View analytics');
    }

    public function __invoke(Request $request, OrderAnalytics $analytics): Renderable
    {
        return $this->view('analytics.warehouse.index', [
            'warehouseValue' => $this->warehouseValue(),
        ]);
    }

    private function warehouseValue(): float
    {
        return Product::where('stock', '>', 0)->sum(DB::raw('stock * compare_price'));
    }
}
