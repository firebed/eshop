<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Controllers\Customer\Controller;
use Illuminate\Contracts\Support\Renderable;

class CheckoutProductController extends Controller
{
    public function __invoke(): Renderable
    {
        return $this->view('checkout.products.wire-index');
    }
}
