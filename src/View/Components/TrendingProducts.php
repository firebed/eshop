<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Collection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\View\Component;

class TrendingProducts extends Component
{
    public BaseCollection $categories;

    public function __construct()
    {
        $trending = Collection::firstWhere('slug', 'trending');

        $this->categories = $trending
            ? $trending->products()
                ->with('category.translation', 'image', 'translation')
                ->get()
                ->groupBy('category.name')

            : collect();
    }

    public function render(): Renderable|string
    {
        return view('eshop::customer.components.trending-products');
    }
}
