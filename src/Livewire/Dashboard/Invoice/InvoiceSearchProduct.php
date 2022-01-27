<?php

namespace Eshop\Livewire\Dashboard\Invoice;

use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class InvoiceSearchProduct extends Component
{
    public bool $showModal = false;

    public string $search = "";
    
    protected $listeners = ['addRow'];

    public function addRow(): void
    {
        $this->showModal = true;
    }

    public function addProduct(int $product_id): void
    {
        $this->showModal = false;
        $this->emit('addProduct', $product_id);
    }

    public function render(): Renderable
    {
        $keys = [];
        if (filled($this->search)) {
            $keys = Product::search($this->search)->keys();
        }
        
        $products = Product::exceptParents()
            ->where('visible', true)
            ->where('available', true)
            ->when(filled($keys), fn($q) => $q->whereKey($keys))
            ->with('image', 'parent.translation', 'translation', 'options')
            ->paginate();
        
        return view('eshop::dashboard.invoice.wire.invoice-search-product', [
            'products' => $products,
        ]);
    }
}