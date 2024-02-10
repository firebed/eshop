<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\View\Component;

class Sales extends Component
{

    public BaseCollection $products;

    public function __construct()
    {
        $this->products = Product::exceptVariants()
            ->visible()
            ->where(fn($q) => $q->onSale()->orWhereHas('variants', fn($q) => $q->visible()->onSale()))
            ->with(['image', 'translations' => fn($q) => $q->where('cluster', 'name')])
            ->with('category.translations')
            ->with(['variants' => fn($q) => $q->select('id', 'parent_id', 'discount', 'price', 'wholesale_price')])
            ->latest('updated_at')
            ->take(30)
            ->get();
    }

    public function render(): Renderable
    {
        return view('eshop::customer.components.sales');
    }
}
