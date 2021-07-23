<?php

namespace Eshop\Livewire\Dashboard\Product;

use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class VariantBulkCreateTable extends Component
{
    public float   $productPrice          = 0;
    public ?string $productSku            = '';
    public array   $variantTypes          = [];
    public array   $variants              = [];
    public array   $combinations          = [];
    public bool    $showCombinationsModal = FALSE;

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

    public function add(array $options = []): void
    {
        $this->variants[] = [
            'options' => $options,
            'sku'     => strtoupper(slugify(array_merge([$this->productSku], $options))),
            'price'   => $this->productPrice,
            'stock'   => 0,
            'barcode' => ''
        ];
    }

    private function getCombinations($arrays): array
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function showCombinationsModal(): void
    {
        $this->showCombinationsModal = TRUE;
        $this->skipRender();
    }

    public function generateCombinations(): void
    {
        $arrays = [];
        foreach ($this->combinations as $id => $combination) {
            $arrays[$id] = array_map('trim', explode(',', $combination));
        }
        $this->variants = [];
        $combinations = $this->getCombinations($arrays);
        foreach ($combinations as $combination) {
            $this->add(collect($combination)->mapWithKeys(fn($c, $k) => [array_search($k, $this->variantTypes) => $c])->all());
        }

        $this->showCombinationsModal = FALSE;
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
