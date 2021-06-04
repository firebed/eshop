<?php

namespace Ecommerce\Events;

use Ecommerce\Models\Cart\Cart;
use Ecommerce\Models\Cart\CartStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Cart       $cart;
    public CartStatus $current_status;
    public ?string    $notesToCustomer;

    /**
     * Create a new event instance.
     *
     * @param Cart        $cart
     * @param CartStatus  $current_status
     * @param string|null $notesToCustomer
     */
    public function __construct(Cart $cart, CartStatus $current_status, ?string $notesToCustomer = null)
    {
        $this->cart = $cart;
        $this->current_status = $current_status;
        $this->notesToCustomer = $notesToCustomer;
    }
}
