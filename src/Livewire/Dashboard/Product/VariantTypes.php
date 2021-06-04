<?php


namespace Ecommerce\Livewire\Dashboard\Product;


use Ecommerce\Models\Product\Product;
use Ecommerce\Models\Product\VariantType;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class VariantTypes extends Component
{
    use SendsNotifications;

    public Product     $product;
    public VariantType $editing;
    public bool        $showModal = false;

    public int  $deleteId = 0;
    public bool $showConfirmDelete = false;

    protected array $rules = [
        'editing.product_id' => 'required|integer',
        'editing.name'       => 'required|string'
    ];

    public function mount(): void
    {
        $this->editing = $this->makeVariantType();
    }

    private function makeVariantType(): VariantType
    {
        return new VariantType(['product_id' => $this->product->id]);
    }

    public function create(): void
    {
        $this->editing = $this->makeVariantType();

        $this->skipRender();
        $this->showModal = true;
    }

    public function edit(VariantType $variantType): void
    {
        $this->editing = $variantType;

        $this->skipRender();
        $this->showModal = true;
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        $this->showConfirmDelete = true;
        $this->skipRender();
    }

    public function save(): void
    {
        $this->validate();

        $this->editing->save();
        $this->showModal = false;
        $this->showSuccessToast('Variant type saved!');
    }

    public function delete(): void
    {
        $variantType = VariantType::find($this->deleteId);
        $variantType->delete();
        $this->reset('deleteId');

        $this->showConfirmDelete = false;
        $this->showSuccessToast('Variant type deleted!');
    }

    public function render(): Renderable
    {
        if (!$this->showModal && $this->editing->getKey()) {
            $this->editing = $this->makeVariantType();
        }

        $variantTypes = $this->product->variantTypes()->get();
        return view('dashboard.product.livewire.variant-types', compact('variantTypes'));
    }
}
