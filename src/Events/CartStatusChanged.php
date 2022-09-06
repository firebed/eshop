<?php

namespace Eshop\Events;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Cart       $cart;
    public CartStatus $status;
    public ?string    $notesToCustomer;
    public bool       $notifyCustomer;

    /**
     * Create a new event instance.
     *
     * @param Cart        $cart
     * @param CartStatus  $status
     * @param string|null $notesToCustomer
     * @param bool        $notifyCustomer
     */
    public function __construct(Cart $cart, CartStatus $status, ?string $notesToCustomer = null, bool $notifyCustomer = true)
    {
        $this->cart = $cart;
        $this->status = $status;
        $this->notesToCustomer = $notesToCustomer;
        $this->notifyCustomer = $notifyCustomer;
    }
}
