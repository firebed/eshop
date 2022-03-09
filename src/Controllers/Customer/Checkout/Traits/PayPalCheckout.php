<?php

namespace Eshop\Controllers\Customer\Checkout\Traits;

use Eshop\Actions\Order\SubmitOrder;
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
                return response()->json($response->result->id);
            }

            $orderId = $request->input('order_id');

            DB::beginTransaction();
            $paypal->capture($orderId);
            (new SubmitOrder())->handle($order, auth()->user(), $orderId, $request->ip());
            DB::commit();
            return response()->json(URL::signedRoute('checkout.completed', [app()->getLocale(), $order->id]));
        } catch (Throwable) {
            DB::rollBack();
            return response()->json(__("Payment was unsuccessful. Please select a different payment method and try again."), 422);
        }
    }
}