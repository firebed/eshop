<?php

namespace Eshop\Actions\Product;

use Eshop\Models\Product\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductMovementsSummary
{
    public function handle(Product $product): Collection
    {
        return $product
            ->loadCount(['movements as not_submitted_orders_count' => fn($q) => $q->whereHas('cart', fn($b) => $b->whereNull('submitted_at'))])
            ->loadSum(['movements as not_submitted_quantity_sum' => fn($q) => $q->whereHas('cart', fn($b) => $b->whereNull('submitted_at'))], 'quantity')
            ->movements()
            ->select("product_id")
            ->selectRaw("COUNT(*) as submitted_orders_count")
            ->selectRaw("SUM(quantity) as total_quantity")
            ->selectRaw("SUM(quantity * price) as total_revenue")
            ->selectRaw("SUM(quantity * (price / (1 + vat))) as total_revenue_without_vat")
            ->selectRaw("SUM(quantity * (price / (1 + vat) - compare_price)) as total_profits")
            ->join('carts', 'carts.id', '=', 'cart_product.cart_id')
            ->whereHas('cart', fn($b) => $b->whereNotNull('submitted_at')->where('status_id', '<', 6))
            ->groupBy('product_id')
            ->get();
    }
}