<?php

namespace Eshop\Livewire\Dashboard\Pos;

use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class PosProductsSearch extends Component
{
    public string $search = "";

    public function render(): Renderable
    {
        $products = collect();
        if (filled($this->search)) {
            $products = Product::select(['id', 'parent_id', 'price'])
                ->visible()
                ->with(['translation', 'parent.translation', 'options', 'image'])
                ->where('has_variants', false)
                ->where(function ($q) {
                    $q->where('id', 'LIKE', "%$this->search%");
                    $q->orWhere('slug', 'LIKE', "%$this->search%");
                    $q->orWhere('sku', 'LIKE', "%$this->search%");
                })
                ->take(15)
                ->get();
        }

        return view('eshop::dashboard.pos.wire.products-search', [
            'products' => $products
        ]);
    }
}