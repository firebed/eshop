<?php

namespace App\Http\Controllers\Customer\Checkout;

use App\Http\Controllers\Controller;
use App\Repository\Contracts\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutPaymentController extends Controller
{
    public function __invoke(Order $order): View|RedirectResponse
    {
        if ($order->isEmpty()) {
            return redirect()->route('customer.checkout.products.index', app()->getLocale());
        }

        return view('customer.checkout.payment.wire-edit');
    }
}
