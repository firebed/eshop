<?php

namespace Eshop\Services\Skroutz\Listeners;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\Payment;
use Eshop\Models\Notification;
use Eshop\Services\Skroutz\Events\SkroutzPayoutReceived;

class SkroutzProcessPayouts
{
    public function handle(SkroutzPayoutReceived $event): void
    {
        $from = "Skroutz";
        $payouts = $event->payouts;
        $date = $event->date;
        $total = $payouts->sum('payoutTotal');

        $carts = Cart::query()
            ->whereIn('reference_id', $payouts->keys())
            ->select('id', 'reference_id', 'total')
            ->with('shippingAddress', 'payment')
            ->get();

        $payments = collect();

        foreach ($payouts as $reference_id => $payout) {
            $cart = $carts->firstWhere('reference_id', $reference_id);

            $payment = [
                'cartId'       => $cart?->id,
                'reference_id' => $reference_id,
                'payoutTotal'  => $payout['payoutTotal'],
                'fees'         => $payout['fees'],
                'cartTotal'    => $cart?->total,
                'customer'     => $cart?->shippingAddress->fullname,
            ];

            if ($cart === null) {
                $payment['error'] = "Δεν βρέθηκε παραγγελία με αυτό το voucher.";
                $payments->push($payment);
                continue;
            }

            if (!floats_equal($cart->total, $payout['fees'] + $payout['payoutTotal'])) {
                $payment['error'] = "Το σύνολο πληρωμής διαφέρει από το σύνολο της παραγγελίας.";
                $payments->push($payment);
                continue;
            }

            if ($cart->payment !== null) {
                $payment['warning'] = "Η παραγγελία έχει ήδη πληρωθεί στις " . $cart->payment->created_at->format('d/m/y H:i');
                $payments->push($payment);
                continue;
            }

            $cart->payment()->save(new Payment(['created_at' => $date]));

            CartEvent::orderPaid($cart->id, $from);
            $payments->push($payment);
        }

        $passed = $payments->reject(fn($p) => isset($p['error']) || isset($p['warning']))->count();

        $notification = sprintf("Λάβατε μια πληρωμή από %s με ποσό %s", $from, format_currency($total));
        $view = view('eshop::dashboard.notification.partials.skroutz-payout', [
            'payments' => $payments,
            'passed'   => $passed
        ]);

        Notification::create([
            'text' => $notification,
            'body' => $view->render(),
        ]);
    }
}
