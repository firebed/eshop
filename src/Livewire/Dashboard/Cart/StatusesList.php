<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Models\Cart\CartStatus;
use Illuminate\View\View;
use Livewire\Component;

class StatusesList extends Component
{
    protected $listeners = ['cartStatusUpdated' => '$refresh'];

    public function render(): View
    {
        $statuses = CartStatus::withCount('carts')->get();
        return view('eshop::dashboard.cart.partials.statuses-list', compact('statuses'));
    }
}
