<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\View\Component;

class NewArrivals extends Component
{

    public BaseCollection $products;

    public function __construct()
    {
        $this->products = Product::exceptVariants()
            ->visible()
            ->where(fn($q) => $q->recent()->orWhereHas('variants', fn($b) => $b->visible()->recent()))
            ->with(['image', 'translations' => fn($q) => $q->where('cluster', 'name')])
            ->with('category.translations')
            ->with(['variants' => fn($q) => $q->visible()->select('id', 'parent_id', 'discount', 'price')])
            ->take(30)
            ->latest('updated_at')
            ->get();
    }

    public function render(): Renderable
    {
        return view('eshop::customer.components.new-arrivals');
    }
}
