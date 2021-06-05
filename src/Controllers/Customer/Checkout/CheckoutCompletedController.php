<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Models\Cart\Cart;
use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class CheckoutCompletedController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param         $locale
     * @param Cart    $cart
     * @return Renderable
     */
    public function __invoke(Request $request, $locale, Cart $cart): Renderable
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        return view('com::customer.checkout.completed.index', compact('cart'));
    }
}
