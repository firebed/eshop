<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Eshop\Services\SpeedEx\Http\SpeedExGetVouchersByDate;
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
        return $this->view('cart.index');
    }

    public function show(Cart $cart): Renderable
    {
        //dd((new SpeedExGetVouchersByDate())->handle(today()));
        if ($cart->isSubmitted()) {
            if (!$cart->isViewed()) {
                $cart->viewed_at = now();
                $cart->save();

                CartEvent::orderViewed($cart->id);
            }

            $assignment = $cart->operators()->firstWhere('user_id', auth()->id());
            $assignment?->pivot?->update(['viewed_at' => now()]);
        }

        return $this->view('cart.show', compact('cart'));
    }

    public function destroy(Cart $cart): RedirectResponse
    {
        $cart->delete();
        return redirect()->route('eshop::dashboard.carts.index');
    }
}
