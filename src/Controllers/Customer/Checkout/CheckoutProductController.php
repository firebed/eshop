<?php

namespace Ecommerce\Controllers\Customer\Checkout;

use Ecommerce\Controllers\Controller;
use Illuminate\View\View;

class CheckoutProductController extends Controller
{
    public function __invoke(): View
    {
        return view('com::customer.checkout.products.wire-index');
    }
}
