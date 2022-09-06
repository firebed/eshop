<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class ShowCartEvents extends Component
{
    public Cart $cart;
    public bool $show = false;

    public function render(): Renderable
    {
        $events = collect();

        if ($this->show) {
            $events = CartEvent::query()
                ->where('cart_id', $this->cart->id)
                ->orderByDesc('id')
                ->with('user')
                ->get();
        }

        return view('eshop::dashboard.cart.wire.show-cart-events', compact('events'));
    }
}