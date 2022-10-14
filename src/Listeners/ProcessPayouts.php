<?php

namespace Eshop\Listeners;

use Eshop\Events\PayoutReceived;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\Payment;
use Eshop\Models\Notification;

class ProcessPayouts
{
    public function handle(PayoutReceived $event): void
    {
        $courierId = $event->courierId;
        $payouts = $event->payouts;
        $from = $event->from;
        $date = $event->date;
        $total = $payouts->sum();

        $carts = Cart::query()
            ->where('shipping_method_id', $courierId)
            ->whereIn('voucher', $payouts->keys())
            ->whereDoesntHave('payment')
            ->select('id')
            ->get();

        foreach ($carts as $cart) {
            $cart->payment()->save(new Payment(['created_at' => $date]));

            CartEvent::orderPaid($cart->id, $from);
        }

        $notification = sprintf("Λάβατε μια πληρωμή από <strong>%s</strong> με ποσό <strong>%s</strong>. %d/%d", $from, format_currency($total), $carts->count(), $payouts->count());

        Notification::create([
            'text'     => $notification,
            'metadata' => $payouts
        ]);
    }
}
