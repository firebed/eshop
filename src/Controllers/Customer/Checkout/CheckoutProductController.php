<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Cart\CartEvent;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;

class CheckoutProductController extends Controller
{
    public function __invoke(Order $order): Renderable
    {
        CartEvent::getCheckoutProducts($order->id);
        
        return $this->view('checkout.products.wire-index');
    }
}
