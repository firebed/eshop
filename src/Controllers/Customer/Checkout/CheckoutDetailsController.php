<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Repository\Contracts\Order;
use Illuminate\Http\RedirectResponse;
use Eshop\Controllers\Controller;
use Illuminate\View\View;

class CheckoutDetailsController extends Controller
{
    public function __invoke(Order $order): View|RedirectResponse
    {
        if ($order->isEmpty()) {
            return redirect()->route('checkout.products.index', app()->getLocale());
        }

        return view('eshop::customer.checkout.details.wire-edit');
    }
}
