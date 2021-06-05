<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Controllers\Controller;
use Illuminate\View\View;

class CheckoutProductController extends Controller
{
    public function __invoke(): View
    {
        return view('eshop::customer.checkout.products.wire-index');
    }
}
