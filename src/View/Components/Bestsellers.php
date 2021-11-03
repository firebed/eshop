<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Collection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\View\Component;

class Bestsellers extends Component
{
    public BaseCollection $products;

    public function __construct()
    {
        $bestsellers = Collection::firstWhere('slug', 'bestsellers');

        $this->products = $bestsellers
            ? $bestsellers->products()
                ->with('category.translation', 'image', 'translation')
                ->get()

            : collect();
    }

    public function render(): Renderable|string
    {
        return view('eshop::customer.components.bestsellers');
    }
}
