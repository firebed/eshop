<?php

namespace Ecommerce\Controllers\Customer\Checkout;

use App\Models\Cart\Cart;
use Ecommerce\Controllers\Controller;
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

        return view('customer.checkout.completed.index', compact('cart'));
    }
}
