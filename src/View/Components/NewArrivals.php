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
            ->recent()
            ->with('category.translation', 'image', 'translation')
            ->with('parent.translation', 'options')
            ->take(30)
            ->latest()
            ->get();
    }

    public function render(): Renderable
    {
        return view('eshop::customer.components.new-arrivals');
    }
}
