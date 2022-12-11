<?php

namespace Eshop\Controllers\Dashboard\MyShipping;

use Eshop\Models\Cart\Voucher;
use Eshop\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class MyShippingWebhookController
{
    public function __invoke(Request $request): Response
    {
        try {
            $event = $request->input('event');
            $courier = $request->input('courier');

            if ($event === 'payout_received') {
                $payouts = $request->collect('payouts');

                $vouchers = Voucher::query()
                    ->whereIn('number', $payouts->keys())
                    ->with('cart')
                    ->get()
                    ->keyBy('number');

                foreach ($vouchers as $number => $voucher) {
                    $payout = $payouts->get($number);

                    $voucher->cart->payment()->updateOrCreate([], [
                        'total' => $payout['amount'],
                        'fees'  => $payout['fees']
                    ]);
                }

                $total = $payouts->sum('amount');
                Notification::create([
                    'text'     => sprintf("%s: Λάβατε μια πληρωμή με ποσό %s", $courier, format_currency($total)),
                    'metadata' => [
                        'keyName' => 'voucher',
                        'payouts' => $payouts
                    ]
                ]);
            }
        } catch (Throwable $e) {
            report($e);

            return response("", 500);
        }

        return response("");
    }
}