<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartStatus;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class StatusesList extends Component
{
    protected $listeners = ['cartStatusUpdated' => '$refresh', 'paymentsUpdated' => '$refresh'];

    public function render(): Renderable
    {
        $statuses = CartStatus::withCount(['carts' => function ($q) {
            $q->when(auth()->user()?->cannot('Manage orders') && auth()->user()?->can('Manage assigned orders'), function ($q) {
                $q->whereHas('operators', fn($b) => $b->where('user_id', auth()->id()));
            });
        }])->get();

        $incomplete_carts_count = Cart::whereNull('submitted_at')->count();
        
        $unpaid_carts = Cart::whereNotNull('submitted_at')->where('status_id', '<', 6)->whereDoesntHave('payment')->count();

        return view('eshop::dashboard.cart.partials.statuses-list', compact('statuses', 'incomplete_carts_count', 'unpaid_carts'));
    }
}
