<?php

namespace Eshop\Controllers\Customer\Checkout\Traits;

use Eshop\Actions\Order\SubmitOrder;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\Payment;
use Eshop\Repository\Contracts\Order;
use Eshop\Services\PayPalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Throwable;

trait PayPalCheckout
{
    protected function checkoutUsingPayPal(Request $request, Order $order): JsonResponse
    {
        $paypal = new PayPalService();
        try {
            if ($request->missing('order_id')) {
                $response = $paypal->create($order);

                CartEvent::create([
                    'cart_id' => $order->id,
                    'user_id' => auth()->id(),
                    'type'    => CartEvent::WARNING,
                    'action'  => CartEvent::CHECKOUT_PAYMENT,
                    'title'   => __("eshop::cart.events.paypal_checkout"),
                    'details' => [
                        'paypal_order_id' => $response->result->id
                    ]
                ]);

                return response()->json($response->result->id);
            }

            $orderId = $request->input('order_id');

            DB::beginTransaction();
            CartEvent::create([
                'cart_id' => $order->id,
                'user_id' => auth()->id(),
                'type'    => CartEvent::SUCCESS,
                'action'  => CartEvent::CHECKOUT_PAYMENT,
                'title'   => __("eshop::cart.events.paypal_checkout"),
                'details' => [
                    'paypal_order_id' => $orderId
                ]
            ]);

            $paypal->capture($orderId);
            (new SubmitOrder())->handle($order, auth()->user(), $orderId, $request->ip());
            $order->payment()->save(new Payment());

            DB::commit();
            return response()->json(URL::signedRoute('checkout.completed', [app()->getLocale(), $order->id]));
        } catch (Throwable $e) {
            logger($e->getMessage());
            DB::rollBack();


            CartEvent::create([
                'cart_id' => $order->id,
                'user_id' => auth()->id(),
                'type'    => CartEvent::ERROR,
                'action'  => CartEvent::CHECKOUT_PAYMENT,
                'title'   => __("eshop::cart.events.paypal_checkout"),
                'details' => $e->getMessage()
            ]);

            return response()->json(__("Payment was unsuccessful. Please select a different payment method and try again."), 422);
        }
    }
}