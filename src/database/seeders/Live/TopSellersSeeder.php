<?php

namespace Database\Seeders\Live;

use App\Models\Cart\CartProduct;
use App\Models\Product\TopSeller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopSellersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $products = CartProduct
            ::select('product_id', DB::raw('COUNT(*) as `orders_count`'))
            ->groupBy('product_id')
            ->orderByDesc('orders_count')
            ->limit(30)
            ->get()
            ->map(fn($i) => [
                'product_id'   => $i->product_id,
                'orders_count' => $i->orders_count
            ])
            ->all();

        TopSeller::truncate();
        TopSeller::insert($products);
    }
}
