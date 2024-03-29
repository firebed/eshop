<?php

namespace Eshop\Providers;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\Country;
use Eshop\Repository\CartRepository;
use Eshop\Repository\Contracts\CartContract;
use Eshop\Repository\Contracts\Order;
use Eshop\Repository\Contracts\ProductContract;
use Eshop\Repository\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(ProductContract::class, ProductRepository::class);

        $this->app->singleton(CartContract::class, CartRepository::class);

        $this->app->singleton('countries', fn() => Country::orderBy('name')->get());

        $this->app->singleton(Order::class, function () {
            if (Auth::check()) {
                return auth()->user()->activeCart()->latest()->firstOrNew();
            }

//            if (session()->has('cart-session-id')) {
//                $sessionId = session('cart-session-id');
//                $cart = Cart::find($sessionId);
//                if ($cart !== NULL && !$cart->isSubmitted()) {
//                    return $cart;
//                }
//                session()->forget('cart-session-id');
//            }

            $request = request();

            if ($request && $request->hasCookie('cart-cookie-id')) {
                $cookieId = $request->cookie('cart-cookie-id');
                $cart = Cart::firstWhere('cookie_id', $cookieId);
                if ($cart !== null && !$cart->isSubmitted()) {
//                    session()->put('cart-session-id', $cart->id);
                    return $cart;
                }
                
                // The cart is submitted or null, we must delete the cookie
                cookie()->queue(cookie()->forget('cart-cookie-id'));
            }

            return new Cart();
        });
    }
}