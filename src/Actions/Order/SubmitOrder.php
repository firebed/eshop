<?php

namespace Eshop\Actions\Order;

use Error;
use Eshop\Events\CartStatusChanged;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartStatus;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Throwable;

class SubmitOrder
{
    public function handle(Cart $cart, ?Authenticatable $user = null, ?string $payment_id = null, ?string $ip = null): void
    {
        if ($cart->isSubmitted()) {
            throw new Error("Cart is already submitted.");
        }

        $this->decrementProductStocks($cart);

        $submitted = CartStatus::firstWhere('name', CartStatus::SUBMITTED);
        $cart->status()->associate($submitted);
        $cart->submitted_at = now();
        $cart->payment_id = $payment_id;
        $cart->ip = $ip;

        $cart->save();

        if (empty($cart->shippingAddress->related_id) && $user) {
            $user->addresses()->save($cart->shippingAddress->replicate(['cluster']));
        }

        DB::afterCommit(static function () use ($cart) {
            session()->forget(['cart-session-id', 'countryShippingMethod', 'countryPaymentMethod']);
            cookie()->queue(cookie()->forget('cart-cookie-id'));

            try {
                event(new CartStatusChanged($cart, $cart->status));
            } catch (Throwable) {
            }
        });
    }

    private function decrementProductStocks(Cart $cart): void
    {
        $products = $cart->products;
        foreach ($products as $cartItem) {
            $cartItem->timestamps = false;
            $cartItem->decrement('stock', $cartItem->pivot->quantity);
        }
    }
}