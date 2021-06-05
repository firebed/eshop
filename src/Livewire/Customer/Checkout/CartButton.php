<?php

namespace Eshop\Livewire\Customer\Checkout;

use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CartButton extends Component
{
    public int $count;

    protected $listeners = ['setCartItemsCount'];

    public function mount(Order $order): void
    {
        $this->count = $order->isNotEmpty() ? $order->items()->count() : 0;
    }

    public function setCartItemsCount($count): void
    {
        $this->count = $count;
    }

    public function render(): Renderable
    {
        return view('com::customer.checkout.partials.cart-button');
    }
}
