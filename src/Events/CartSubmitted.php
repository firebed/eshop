<?php

namespace Eshop\Events;

use Eshop\Models\Cart\Cart;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }
}
