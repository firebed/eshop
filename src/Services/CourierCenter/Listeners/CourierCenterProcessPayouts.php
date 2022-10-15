<?php

namespace Eshop\Services\CourierCenter\Listeners;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\Payment;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Models\Notification;
use Eshop\Services\CourierCenter\Events\CourierCenterPayoutReceived;

class CourierCenterProcessPayouts
{
    public function handle(CourierCenterPayoutReceived $event): void
    {
        $courierCenter = ShippingMethod::firstWhere('name', 'Courier Center');

        $from = $courierCenter->name;
        $payouts = $event->payouts;
        $date = $event->date;
        $total = $payouts->sum();

        $carts = Cart::query()
            ->where('shipping_method_id', $courierCenter->id)
            ->whereIn('voucher', $payouts->keys())
            ->select('id', 'voucher', 'total')
            ->with('shippingAddress', 'payment')
            ->get();

        $payments = collect();

        foreach ($payouts as $voucher => $payoutTotal) {
            $cart = $carts->firstWhere('voucher', $voucher);

            $payment = [
                'cartId'      => $cart?->id,
                'voucher'     => $voucher,
                'payoutTotal' => $payoutTotal,
                'cartTotal'   => $cart?->total,
                'customer'    => $cart?->shippingAddress->fullname,
            ];

            if ($cart === null) {
                $payment['error'] = "Δεν βρέθηκε παραγγελία με αυτό το voucher.";
                $payments->push($payment);
                continue;
            }

            if (!floats_equal($cart->total, $payoutTotal)) {
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
        $view = view('eshop::dashboard.notification.partials.courier-center-payout', [
            'payments' => $payments,
            'passed'   => $passed,
        ]);

        Notification::create([
            'text' => $notification,
            'body' => $view->render()
        ]);
    }
}
