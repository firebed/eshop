<?php

namespace App\Http\Livewire\Dashboard\Cart;

use App\Models\Cart\CartStatus;
use Illuminate\View\View;
use Livewire\Component;

class StatusesList extends Component
{
    protected $listeners = ['cartStatusUpdated' => '$refresh'];

    public function render(): View
    {
        $statuses = CartStatus::withCount('carts')->get();
        return view('dashboard.cart.partials.statuses-list', compact('statuses'));
    }
}
