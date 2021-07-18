<?php


namespace Eshop\Livewire\Dashboard\Product;


use Eshop\Models\Product\Category;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class VariantTypes extends Component
{
    public array $variantTypes = [];

    public function add(): void
    {
        $this->variantTypes[] = ['id' => '', 'name' => ''];
    }

    public function remove(int $index): void
    {
        unset($this->variantTypes[$index]);
        $this->variantTypes = array_values($this->variantTypes);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.product.wire.variant-types');
    }
}