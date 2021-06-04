<?php

namespace Ecommerce\View\Components;

use Ecommerce\Models\Product\TopSeller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class TopSellers extends Component
{
    public Collection $products;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->products = TopSeller::query()
            ->with('product.translation', 'product.image', 'product.parent.translation')
            ->orderByDesc('orders_count')
            ->limit(8)
            ->get()
            ->pluck('product');
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('com::components.top-sellers');
    }
}
