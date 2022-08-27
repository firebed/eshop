<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartChannel;
use Eshop\Models\Cart\CartStatus;

class UpdateOrder
{
    public function handle($event): void
    {
        $changes = $event->changes;
        $order = $event->order;
        $cart = Cart::where('reference_id', $order->code)->where('channel', CartChannel::SKROUTZ)->first();

        if (isset($changes->state)) {
            $oldState = $cart->status;
            $newState = $this->getStatus($changes->state->new);

            if ($newState === null) {
                return;
            }
            
            if ($oldState->isCapturingStocks() && $newState->isReleasingStocks()) {
                $this->incrementStocks($cart);
            } else if ($oldState->isReleasingStocks() && $newState->isCapturingStocks()) {
                $this->decrementStocks($cart);
            }

            $cart->status()->associate($newState);
            $cart->save();
        }

        if (isset($changes->courier_voucher)) {
            $cart->voucher = $changes->courier_tracking_codes->new[0];
            $cart->save();
        }
    }

    private function getStatus($state): ?CartStatus
    {
        return match ($state) {
            'open'                           => CartStatus::firstWhere('name', CartStatus::SUBMITTED),
            'accepted'                       => CartStatus::firstWhere('name', CartStatus::APPROVED),
            'rejected'                       => CartStatus::firstWhere('name', CartStatus::REJECTED),
            'cancelled', 'expired'           => CartStatus::firstWhere('name', CartStatus::CANCELLED),
            'dispatched'                     => CartStatus::firstWhere('name', CartStatus::SHIPPED),
            'partially_returned', 'returned' => CartStatus::firstWhere('name', CartStatus::RETURNED),
            default                          => null
        };
    }

    private function incrementStocks(Cart $cart): void
    {
        foreach ($cart->products as $product) {
            $product->timestamps = false;
            $product->increment('stock', $product->pivot->quantity);
            $product->save();
        }
    }

    private function decrementStocks(Cart $cart): void
    {
        foreach ($cart->products as $product) {
            $product->timestamps = false;
            $product->decrement('stock', $product->pivot->quantity);
            $product->save();
        }
    }
}