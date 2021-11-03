<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Controllers\Customer\Controller;
use Illuminate\View\View;

class CheckoutProductController extends Controller
{
    public function __invoke(): View
    {
        return $this->view('checkout.products.wire-index');
    }
}
