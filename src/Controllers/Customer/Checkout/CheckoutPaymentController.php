<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Controllers\Controller;
use Eshop\Repository\Contracts\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutPaymentController extends Controller
{
    public function __invoke(Order $order): View|RedirectResponse
    {
        if ($order->isEmpty()) {
            return redirect()->route('eshop::checkout.products.index', app()->getLocale());
        }

        return view('eshop::customer.checkout.payment.wire-edit');
    }
}
