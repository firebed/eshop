<?php

namespace Eshop\Mail;

use Eshop\Models\Cart\Cart;
use Illuminate\Mail\Mailable;

class OrderShippedMail extends Mailable
{
    public Cart    $cart;
    public ?string $notesToCustomer;

    public function __construct(Cart $cart, ?string $notesToCustomer = null)
    {
        $this->cart = $cart;
        $this->notesToCustomer = $notesToCustomer;
    }

    public function build(): OrderShippedMail
    {
        $this->cart->products->loadMissing('parent.translation', 'image', 'translation');
        return $this->markdown('emails.order.shipped');
    }
}
