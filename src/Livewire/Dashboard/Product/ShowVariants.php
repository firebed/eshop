<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Livewire\Dashboard\Product\Traits\SavesVariant;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\Unit;
use Eshop\Models\Product\VariantType;
use Eshop\Models\Product\Vat;
use Firebed\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Livewire\Traits\Datatable\WithSelections;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowVariants extends Component
{
    use WithSelections;
    use SendsNotifications;
    use SavesVariant;
    use DeletesRows;

    public $product;

    public $showModal         = FALSE;
    public $showDiscountModal = FALSE;
    public $showPriceModal    = FALSE;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function showDiscountModal(): void
    {
        $this->skipRender();

        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Now rows selected!');
            return;
        }

        $this->showDiscountModal = TRUE;
    }

    public function setDiscount(): void
    {
        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Now rows selected!');
            $this->skipRender();
            return;
        }

        Product::whereKey($this->selected())->update([
            'discount' => $this->global_discount
        ]);

        $this->showSuccessToast('Discount set to ' . format_percent($this->global_discount));
        $this->showDiscountModal = FALSE;
    }

    public function showPriceModal(): void
    {
        $this->skipRender();

        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Now rows selected!');
            return;
        }

        $this->showPriceModal = TRUE;
    }

    public function setPrice(): void
    {
        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Now rows selected!');
            $this->skipRender();
            return;
        }

        Product::whereKey($this->selected())->update([
            'price' => $this->global_price
        ]);

        $this->showSuccessToast('Price set to ' . format_currency($this->global_price));
        $this->showPriceModal = FALSE;
    }

    protected function deleteRows(): ?int
    {
        return DB::transaction(function () {
            Product::findMany($this->selected())->each->delete();
            return $this->countSelected();
        });
    }

    /**
     * Get the product's all variants
     *
     * @return Collection
     */
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

    protected function getModel(): Product
    {
        return $this->variant;
    }

    protected function getModels(): Collection
    {
        return $this->variants;
    }

    /**
     * Get the view that represent the component.
     *
     * @return Renderable
     */
    public function render(): Renderable
    {
        $data['variants'] = $this->variants;
        $data['variantTypes'] = $this->variantTypes;
        if ($this->showModal) {
            $data['vats'] = Vat::all();
            $data['units'] = Unit::all();
        }
        return view('com::dashboard.product-variant.livewire.show-variants', $data);
    }
}
