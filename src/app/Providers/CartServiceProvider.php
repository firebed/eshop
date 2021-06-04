<?php

namespace App\Providers;

use App\Models\Cart\Cart;
use App\Repository\CartRepository;
use App\Repository\Contracts\CartContract;
use App\Repository\Contracts\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(CartContract::class, CartRepository::class);

        $this->app->singleton(Order::class, function () {
            if (Auth::check()) {
                return user()->activeCart()->firstOrNew();
            }

//            if (session()->has('cart-session-id')) {
//                $sessionId = session('cart-session-id');
//                $cart = Cart::find($sessionId);
//                if ($cart !== NULL && !$cart->isSubmitted()) {
//                    return $cart;
//                }
//                session()->forget('cart-session-id');
//            }

            if (request()->hasCookie('cart-cookie-id')) {
                $cookieId = request()->cookie('cart-cookie-id');
                $cart = Cart::firstWhere('cookie_id', $cookieId);
                if ($cart !== NULL && !$cart->isSubmitted()) {
//                    session()->put('cart-session-id', $cart->id);
                    return $cart;
                }
                cookie()->queue(cookie()->forget('cart-cookie-id'));
            }

            return new Cart();
        });
    }
}
