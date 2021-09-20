<?php

namespace App\Http\Controllers\Checkout;

use Eshop\Controllers\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutCompletedController extends Controller
{
    public function __invoke(Request $request, $locale, Cart $cart): Renderable|RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('checkout.products.index', $locale);
        }

        $cart->load('products.image', 'products.translation', 'products.parent.translation', 'products.options');

        return view('checkout.completed.index', compact('cart'));
    }
}