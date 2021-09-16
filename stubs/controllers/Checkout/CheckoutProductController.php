<?php

namespace App\Http\Controllers\Checkout;

use Eshop\Controllers\Controller;
use Illuminate\View\View;

class CheckoutProductController extends Controller
{
    public function __invoke(): View
    {
        return view('checkout.products.wire-index');
    }
}
