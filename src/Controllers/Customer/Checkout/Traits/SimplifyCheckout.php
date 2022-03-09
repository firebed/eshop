<?php

namespace Eshop\Controllers\Customer\Checkout\Traits;

use Eshop\Repository\Contracts\Order;
use Eshop\Services\Simplify\SimplifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait SimplifyCheckout
{
    protected function checkoutUsingSimplify(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'cc_number' => ['required', 'int', 'digits:16'],
            'cc_expiry' => ['required', 'string', 'date:mm/yy'],
            'cc_cvc'    => ['required', 'string']
        ]);

        $simplify = new SimplifyService();
        if ($request->missing('token')) {
            $expiry = $request->date('cc_expiry', 'mm/yy');

            $result = $simplify->createCardToken(
                $order->total,
                $request->input('cc_number'),
                $expiry->format('mm'),
                $expiry->format('yy'),
                $request->input('cvc'),
                $order->shippingAddress->city
            );
            return response()->json($result);
        }
    }
}