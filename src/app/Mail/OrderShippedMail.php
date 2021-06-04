<?php

namespace App\Mail;

use App\Models\Cart\Cart;
use Illuminate\Mail\Mailable;

class OrderShippedMail extends Mailable
{
    public Cart    $cart;
    public ?string $notesToCustomer;

    /**
     * Create a new message instance.
     *
     * @param Cart        $cart
     * @param string|null $notesToCustomer
     */
    public function __construct(Cart $cart, ?string $notesToCustomer = null)
    {
        $this->cart = $cart;
        $this->notesToCustomer = $notesToCustomer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): OrderShippedMail
    {
        $this->cart->products->loadMissing('parent.translation', 'image', 'translation');
        return $this->markdown('emails.order-shipped');
    }
}
