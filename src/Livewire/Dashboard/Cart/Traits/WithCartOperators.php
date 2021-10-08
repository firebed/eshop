<?php

namespace Eshop\Livewire\Dashboard\Cart\Traits;

use Eshop\Models\Cart\Cart;
use Illuminate\Support\Facades\DB;

trait WithCartOperators
{
    public bool  $showOperatorsModal = false;
    public array $operator_ids       = [];

    public function showOperators(): void
    {
        $this->showOperatorsModal = true;
        $this->skipRender();
    }

    public function saveOperators(): void
    {
        DB::transaction(function () {
            $carts = Cart::findMany($this->selected);
            foreach ($carts as $cart) {
                $cart->operators()->sync($this->operator_ids);
            }
        });

        $this->operator_ids = [];
        $this->showOperatorsModal = false;
        $this->showSuccessToast('Operators saved!');
    }
}