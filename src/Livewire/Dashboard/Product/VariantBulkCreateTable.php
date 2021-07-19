<?php

namespace Eshop\Livewire\Dashboard\Product;

use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class VariantBulkCreateTable extends Component
{
    public float   $productPrice = 0;
    public ?string $productSku   = '';
    public array   $variantTypes = [];
    public array   $variants     = [];

    public function mount(): void
    {
        if (empty($this->variants)) {
            for ($i = 0; $i < 3; $i++) {
                $this->add();
            }
        }
    }

    public function updatedVariants($i, $k): void
    {
        [$index, $column] = explode('.', $k);
        if ($column !== 'options') {
            return;
        }
        $this->variants[$index]['sku'] = implode('-', [$this->productSku] + $this->variants[$index]['options']);

        $this->skipRender();
    }

    public function add(): void
    {
        $this->variants[] = [
            'options' => [],
            'sku'     => $this->productSku . '-',
            'price'   => $this->productPrice,
            'stock'   => 0,
            'barcode' => ''
        ];
    }

    public function remove(int $index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.variant.wire.variant-bulk-create');
    }
}
