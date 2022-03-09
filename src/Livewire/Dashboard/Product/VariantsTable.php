<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Actions\Audit\AuditModel;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VariantsTable extends Component
{
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
            ->get()
            ->sortBy('option_values', SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function getVariantTypesProperty(): Collection
    {
        return VariantType::where('product_id', $this->product->id)->get();
    }

    public function toggleVisible(array $ids, bool $visible, AuditModel $audit): void
    {
        DB::transaction(function() use ($ids, $visible, $audit) {
            Product::whereKey($ids)->update([
                'visible' => $visible
            ]);
            
            $variants = $this->variants;
            $variants->load('manufacturer', 'translations', 'seos', 'unit', 'parent.translations');
            foreach ($this->variants as $variant) {
                $audit->handle($variant);
            }
            
            $this->showSuccessToast("Οι αλλαγές αποθηκεύτηκαν!");
        });
    }

    public function addWatermark(array $ids): void
    {
        if (empty($ids)) {
            return;
        }
        
        $products = Product::whereKey($ids)->with('image')->get();
        foreach ($products as $product) {
            $product->addWatermark();
        }
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
        
        return view('eshop::dashboard.variant.wire.variants-table', [
            'variants'     => $this->variants,
            'variantTypes' => $this->variantTypes,
            'options'      => $options
        ]);
    }
}
