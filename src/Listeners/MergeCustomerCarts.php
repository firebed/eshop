<?php

namespace Eshop\Listeners;


use Eshop\Models\Cart\Cart;
use Illuminate\Database\Eloquent\Model;

class MergeCustomerCarts
{
    public function handle(): void
    {
        if (self::mergeCarts()) {
            session()->flash('guest-cart-merged-with-user-cart');
        }
    }

    public static function mergeCarts(): bool
    {
        cookie()->queue(cookie()->forget('cart-cookie-id'));

        $previous = auth()->user()?->activeCart()->first();

        if ($previous === NULL && session()->missing('cart-session-id')) {
            return FALSE;
        }

        $merged = FALSE;
        if (session()->has('cart-session-id')) {
            $session = Cart::find(session('cart-session-id'));
            session()->forget('cart-session-id');

            if ($previous === NULL) {
                self::updateCart($session);
                return FALSE;
            }
            
            if ($session !== null && $session->isNot($previous)) {
                $products = $session->pluckProductQuantities();
                $previous->syncProducts($products);
                $merged = $session->delete();
            }
        }

        self::updateCart($previous);
        return $merged;
    }

    private static function updateCart(Cart|Model $cart): void
    {
        $cart->user()->associate(auth()->user());
        $cart->ip = request()?->ip();
        $cart->email = auth()->user()->email;
        $cart->refreshProducts();
        $cart->save();
    }
}
