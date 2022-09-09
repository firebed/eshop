<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Cart\Cart;
use Eshop\Repository\Contracts\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ResumeCart
{
    public function handle(Cart $cart): void
    {
        if (Auth::check()) {
            $order = app(Order::class);
            $this->mergeCarts($cart, $order);

            return;
        }

        $cookie = Cookie::get('cart-cookie-id');
        if ($cookie !== null) {
            $cookieCart = Cart::firstWhere('cookie_id', $cookie);
        }

        // Get the user here, according to eshop config the cart
        // might be deleted when merged.
        $user = $cart->user;

        $c = $this->mergeCarts($cart, $cookieCart ?? null);

        if ($user !== null) {
            session()->put('cart-session-id', $c->id);

            Auth::login($user);
        }
    }

    private function mergeCarts(Cart $cartFromEmail, Cart|null $cartFromCookie): Cart
    {
        $mergeType = eshop('cart.abandoned.merge_abandoned', 'cookie');

        if ($mergeType === null || $cartFromCookie === null || $cartFromCookie->getKey() === null) {
            $this->updateCart($cartFromEmail);

            return $cartFromEmail;
        }

        if ($mergeType === 'cookie') {
            $this->syncProducts($cartFromCookie, $cartFromEmail, $cartFromCookie);
            $this->updateCart($cartFromEmail);

            return $cartFromEmail;
        }

        // Here we assume that the merge type is 'email'
        $this->syncProducts($cartFromEmail, $cartFromCookie, $cartFromEmail);
        $this->updateCart($cartFromCookie, $cartFromEmail->email);

        return $cartFromCookie;
    }

    private function syncProducts(Cart $source, Cart $target, Cart $delete): void
    {
        if ($source->is($target)) {
            return;
        }

        $products = $source->pluckProductQuantities();
        $target->syncProducts($products);

        $delete->delete();

        session()->flash('guest-cart-merged-with-user-cart');
    }

    private function updateCart(Cart $cart, string $fallbackEmail = null): void
    {
        if (Auth::guest()) {
            $cart->cookie_id = (string)Str::uuid();
            cookie()->queue('cart-cookie-id', $cart->cookie_id, now()->addMonths(2)->diffInMinutes());
        } else {
            $cart->user()->associate(auth()->user());
        }

        $cart->ip = request()->ip();
        $cart->email ??= $fallbackEmail;
        $cart->refreshProducts();
        $cart->save();
    }
}