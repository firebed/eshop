<?php


namespace Ecommerce\Livewire\Dashboard\Product\Traits;


trait DeletesProduct
{
    public bool $confirmDelete = false;

    public function delete(): void
    {
        $this->product->delete();
        $this->redirectRoute('products.index');
    }
}
