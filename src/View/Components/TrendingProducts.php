<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\View\Component;

class TrendingProducts extends Component
{

    public BaseCollection $products;

    public function __construct()
    {
        $this->products = Product::exceptVariants()
            ->visible()
            ->whereHas("collections", fn($q) => $q->whereSlug('trending'))
            ->with(['image', 'translations' => fn($q) => $q->where('cluster', 'name')])
            ->with('category.translations')
            ->with(['variants' => fn($q) => $q->visible()->select('id', 'parent_id', 'discount', 'price', 'wholesale_price')])
            ->latest()
            ->take(30)
            ->get();
    }

    public function render(): Renderable
    {
        return view('eshop::customer.components.trending-products');
    }
}
