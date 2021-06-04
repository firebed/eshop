<?php

namespace App\Http\Controllers\Customer\Checkout;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CheckoutProductController extends Controller
{
    public function __invoke(): View
    {
        return view('customer.checkout.products.wire-index');
    }
}
