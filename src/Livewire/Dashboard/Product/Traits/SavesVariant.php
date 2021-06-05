<?php


namespace Eshop\Livewire\Dashboard\Product\Traits;


use Eshop\Models\Product\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait SavesVariant
{
    use CreatesEmptyProduct {
        makeProduct as baseMakeProduct;
    }

    use SavesProductImage;
    use WithVariantAttributes;

    public float  $global_discount = 0;
    public float  $global_price    = 0;
    public string $search          = "";
    public array  $variant_values  = [];

    public Product $variant;

    public function mountSavesVariant(): void
    {
        $this->variant = $this->makeProduct();
    }

    public function renderingSavesVariant(): void
    {
        // Unload model if it's not needed
        if (!$this->showModal && $this->variant->getKey()) {
            $this->variant = $this->makeProduct();
        }
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->resetImage();

        $this->variant = $this->makeProduct();
        $first = $this->variants->first();
        if ($first) {
            $this->variant->vat = $first->vat;
            $this->variant->price = $first->price;
            $this->variant->compare_price = $first->compare_price;
            $this->variant->unit_id = $first->unit_id;
            $this->variant->weight = $first->weight;
        }

        $this->variant_values = [];
        foreach ($this->variantTypes as $vt) {
            $this->variant_values[$vt->id] = '';
        }

//        $this->skipRender();
        $this->showModal = TRUE;
    }

    public function edit(Product $variant): void
    {
        $this->resetValidation();
        $this->resetImage();

        $this->variant = $variant;

        $this->variant_values = [];
        $options = $this->variant->options()->get();
        foreach ($this->variantTypes as $vt) {
            $this->variant_values[$vt->id] = $options->find($vt->id)->pivot->value ?? '';
        }

//        $this->skipRender();
        $this->showModal = TRUE;
    }

    public function updatedVariantValues(): void
    {
        if ($this->variant->id === NULL) {
            $this->variant->slug = slugify($this->variant_values);
        }
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function() {
            if ($this->variant->save()) {
                $this->variant->options()->sync($this->mapVariantTypes());
                $this->saveImage();
            }
        });
        $this->showSuccessToast('Variant saved!');
        $this->showModal = FALSE;
    }

    private function mapVariantTypes(): Collection
    {
        return collect($this->variant_values)->map(fn($value, $key) => [
            'variant_type_id' => $key,
            'value'           => $value
        ]);
    }

    protected function makeProduct(): Product
    {
        $variant = $this->baseMakeProduct();
        $variant->parent_id = $this->product->id;
        $variant->category_id = $this->product->category_id;
        $variant->manufacturer_id = $this->product->manufacturer_id;
        return $variant;
    }
}
