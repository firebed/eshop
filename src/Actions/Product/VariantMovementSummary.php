<?php

namespace Eshop\Actions\Product;

use Eshop\Models\Product\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariantMovementSummary
{
    public function handle(Product $product): Collection
    {
        return $product
            ->variants()
            ->withSum(['movements as not_submitted_quantity_sum' => fn($q) => $q->whereHas('cart', fn($b) => $b->whereNull('submitted_at'))], 'quantity')
            ->withCount(['movements as submitted_orders_count' => fn($q) => $q->whereHas('cart', fn($b) => $b->whereNotNull('submitted_at'))])
            ->with(['movements' => function (HasMany $q) {
                return $q->selectRaw("product_id")
                    ->selectRaw("SUM(quantity) as total_quantity")
                    ->selectRaw("SUM(quantity * price) as total_revenue")
                    ->selectRaw("SUM(quantity * (price / (1 + vat))) as total_revenue_without_vat")
                    ->selectRaw("SUM(quantity * (price / (1 + vat) - compare_price)) as total_profits")
                    ->whereHas('cart', fn($b) => $b->whereNotNull('submitted_at')->where('status_id', '<', 6))
                    ->groupBy('product_id');
            }])
            ->with('image', 'variantOptions.translation')
            ->get()
            ->sortBy('option_values', SORT_NATURAL | SORT_FLAG_CASE)
            ->map(function (Product $product) {
                $movement = $product->movements->first();
                $product->total_sales_quantity = $movement->total_quantity ?? 0;
                $product->total_revenue = $movement->total_revenue ?? 0;
                $product->total_revenue_without_vat = $movement->total_revenue_without_vat ?? 0;
                $product->total_profits = $movement->total_profits ?? 0;
                return $product->unsetRelation('movements');
            });
    }
}