<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

class VariantsTable extends Component
{
    use WithSelections;
    use SendsNotifications;

    public $product;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function getVariantsProperty(): Collection
    {
        return Product::query()
            ->where('parent_id', $this->product->id)
            ->when($this->search, function ($q, $v) {
                $q->where(function ($b) use ($v) {
                    $b->where('sku', 'LIKE', "%$v%");
                    $b->orWhereHas('options', fn($b) => $b->where('value', 'LIKE', "%$v%"));
                });
            })
            ->with('options', 'category', 'image')
            ->get();
    }

    public function getVariantTypesProperty(): Collection
    {
        return VariantType::where('product_id', $this->product->id)->get();
    }

    protected function getModels(): Collection
    {
        return $this->variants;
    }

    public function render(): Renderable
    {
        $options = $this->variants
            ->pluck('options')
            ->collapse()
            ->groupBy('pivot.variant_type_id')
            ->map(fn($g) => $g->pluck('pivot.value')->unique()->sort());
        ;
        return view('eshop::dashboard.variant.wire.variants-table', [
            'variants'     => $this->variants,
            'variantTypes' => $this->variantTypes,
            'options'      => $options
        ]);
    }
}
