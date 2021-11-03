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
        $statuses = CartStatus::withCount(['carts' => function($q) {
            $q->when(auth()->user()?->cannot('Manage orders') && auth()->user()?->can('Manage assigned orders'), function($q) {
                $q->whereHas('operators', fn($b) => $b->where('user_id', auth()->id()));
            });
        }])->get();
        return view('eshop::dashboard.cart.partials.statuses-list', compact('statuses'));
    }
}
