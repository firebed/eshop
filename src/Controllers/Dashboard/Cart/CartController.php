<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Cart::class, 'cart');
    }

    public function index(): Renderable
    {
        return view('eshop::dashboard.cart.index');
    }

    public function show(Cart $cart): Renderable
    {
        if (!$cart->isViewed()) {
            $cart->viewed_at = now();
            $cart->save();
        }
        
        $assignment = $cart->operators()->firstWhere('user_id', auth()->id());
        $assignment?->pivot?->update(['viewed_at' => now()]);
        
        return view('eshop::dashboard.cart.show', compact('cart'));
    }

    public function destroy(Cart $cart): RedirectResponse
    {
        $cart->delete();
        return redirect()->route('eshop::dashboard.carts.index');
    }
}
