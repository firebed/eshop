<?php

namespace Eshop\Livewire\Dashboard\Label;

use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class LabelsTable extends Component
{
    public array  $labels = [];
    public string $search = '';

    public function addProduct(int $product_id): void
    {
        $this->labels[$product_id] = 1;
    }

    public function removeProduct(int $product_id): void
    {
        unset($this->labels[$product_id]);
    }
    
    public function render(): Renderable
    {
        $products = Product::with('image', 'translation', 'parent.translation', 'options')
            ->whereKey(array_keys($this->labels))
            ->get();

        $search_results = collect();
        if (filled($this->search)) {
            $keys = Product::search(trim($this->search))->take(10)->keys();
            
            $search_results = Product::select(['id', 'parent_id', 'price', 'stock'])
                ->whereKey($keys)
                ->with(['translation', 'parent.translation', 'options', 'image'])
                ->get();
        }

        return view('eshop::dashboard.label.wire.labels-table', compact('products', 'search_results'));
    }
}