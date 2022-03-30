<?php

namespace Eshop\Mail;

use Eshop\Models\Cart\Cart;
use Illuminate\Mail\Mailable;

class OrderSubmittedMail extends Mailable
{
    public Cart    $cart;
    public ?string $notesToCustomer;

    public function __construct(Cart $cart, ?string $notesToCustomer = NULL)
    {
        $this->cart = $cart;
        $this->notesToCustomer = $notesToCustomer;
    }

    public function build(): OrderSubmittedMail
    {
        $this->cart->products->loadMissing('parent.translation', 'image', 'translation');

        return $this->markdown('eshop::customer.emails.order.submitted');
    }
}
