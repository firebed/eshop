<?php


namespace App\Http\Livewire\Dashboard\Cart\Traits;


use App\Repository\Contracts\CartContract;
use Illuminate\Support\Facades\DB;

trait AppliesBulkDiscount
{
    public float $global_discount   = 0;
    public bool  $showDiscountModal = false;

    public function openDiscountModal(): void
    {
        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Please select items');
            return;
        }

        $this->global_discount = 0;
        $this->showDiscountModal = true;
        $this->skipRender();
    }

    public function saveDiscount(CartContract $contract): void
    {
        if ($this->doesntHaveSelections()) {
            $this->showWarningToast('Please select items');
            return;
        }

        $this->validate(['global_discount' => 'required|numeric|gte:0|lte:100']);

        DB::transaction(fn () => $contract->setDiscount($this->cart, $this->global_discount, $this->selected));

        $this->emit('cart-items-discount-updated');
        $this->showDiscountModal = false;
        $this->showSuccessToast('Discounts saved!', 'The discount of ' . count($this->selected) . ' products changed to ' . format_percent($this->global_discount) . '.');
    }
}
