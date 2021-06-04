<?php

namespace Ecommerce\Controllers\Customer\Checkout;

use Ecommerce\Controllers\Controller;
use Ecommerce\Repository\Contracts\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutPaymentController extends Controller
{
    public function __invoke(Order $order): View|RedirectResponse
    {
        if ($order->isEmpty()) {
            return redirect()->route('com::customer.checkout.products.index', app()->getLocale());
        }

        return view('com::customer.checkout.payment.wire-edit');
    }
}
