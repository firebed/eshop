<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Models\Cart\Cart;
use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutCompletedController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param         $locale
     * @param Cart    $cart
     * @return Renderable|RedirectResponse
     */
    public function __invoke(Request $request, $locale, Cart $cart): Renderable|RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('checkout.products.index', $locale);
        }

        return view('eshop::customer.checkout.completed.index', compact('cart'));
    }
}
