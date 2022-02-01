<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Eshop\Models\Cart\CartProduct;

trait AppliesBulkVat
{
    public float $global_vat   = 0;
    public bool  $showVatModal = false;

    public function openVatModal(): void
    {
        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Please select items');
            return;
        }

        $this->global_vat = 0;
        $this->showVatModal = true;
        $this->skipRender();
    }

    public function saveVat(): void
    {
        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Please select items');
            return;
        }

        $this->validate(['global_vat' => 'required|numeric|gte:0|lte:100']);

        CartProduct::whereKey($this->selected)
            ->update(['vat' => $this->global_vat]);

        $this->emit('cart-items-vat-updated');
        $this->showVatModal = false;
        $this->showSuccessToast('Vats saved!', 'The vat of ' . count($this->selected) . ' products changed to ' . format_percent($this->global_vat) . '.');
    }
}
