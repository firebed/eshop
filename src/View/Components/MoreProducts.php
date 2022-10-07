<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class MoreProducts extends Component
{
    public Collection $products;

    public function __construct(Product $product)
    {
        $productIds = DB::table('product_aggregations')
            ->where('category_id', $product->category_id)
            ->limit(6)
            ->orderByDesc('total_sales')
            ->pluck('product_id');

        $this->products = Product::whereKey($productIds)
            ->with('category.translation', 'parent.translation', 'image', 'translation', 'options.translation')
            ->get();

        (new \Illuminate\Database\Eloquent\Collection($this->products->pluck('options')->collapse()->pluck('pivot')))->load('translations');
    }

    public function render(): Renderable
    {
        return view('eshop::customer.components.more-products');
    }
}