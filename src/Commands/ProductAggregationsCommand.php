<?php

namespace Eshop\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductAggregationsCommand extends Command
{
    protected $signature = 'products:aggregate';

    protected $description = 'Creates aggregations for products.';

    public function handle(): void
    {
        DB::table('product_aggregations')->truncate();

        $aggregations = $this->aggregateProducts();
        $aggregations->transform(fn($a) => (array)$a)
            ->chunk(1000)
            ->each(function ($chunk) {
                DB::table('product_aggregations')->insert($chunk->all());
            });
    }

    private function aggregateProducts(): Collection
    {
        return DB::table('cart_product')
            ->selectRaw('cart_product.product_id, MIN(products.parent_id) as parent_id, MIN(products.category_id) as category_id, COUNT(cart_product.product_id) as total_sales, SUM(cart_product.quantity) as total_quantities, ROUND(SUM(cart_product.quantity * cart_product.price * (1 - cart_product.discount)), 2) as total_price')
            ->leftJoin('carts', 'carts.id', '=', 'cart_product.cart_id')
            ->leftJoin('products', 'products.id', '=', 'cart_product.product_id')
            ->whereNotNull('carts.submitted_at')
            ->whereNull('products.deleted_at')
            ->whereNull('cart_product.deleted_at')
            ->whereIn('carts.status_id', [1, 2, 3, 4, 5])
            ->where('products.visible', true)
            ->where('products.available', true)
            //->where('products.stock', '>', 0)
            ->groupBy('cart_product.product_id', 'category_id')
            ->orderByDesc('total_sales')
            ->get();
    }
}