<?php


namespace Eshop\Livewire\Dashboard\Product;


use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class VariantTypes extends Component
{
    public array $variantTypes = [];

    public function add(): void
    {
        $this->variantTypes[] = ['id' => '', 'name' => ''];
        $this->dispatchBrowserEvent('updated-variant-types', count($this->variantTypes));
    }

    public function remove(int $index): void
    {
        unset($this->variantTypes[$index]);
        $this->variantTypes = array_values($this->variantTypes);
        $this->dispatchBrowserEvent('updated-variant-types', count($this->variantTypes));
    }

    public function moveUp(int $index): void
    {
        if ($index === 0) {
            return;
        }
        
        $temp = $this->variantTypes[$index - 1];
        $this->variantTypes[$index - 1] = $this->variantTypes[$index];
        $this->variantTypes[$index] = $temp;
    }

    public function moveDown(int $index): void
    {
        if ($index === count($this->variantTypes) - 1) {
            return;
        }
        
        $temp = $this->variantTypes[$index + 1];
        $this->variantTypes[$index + 1] = $this->variantTypes[$index];
        $this->variantTypes[$index] = $temp;
    }
    
    public function render(): Renderable
    {
        return view('eshop::dashboard.product.wire.variant-types');
    }
}