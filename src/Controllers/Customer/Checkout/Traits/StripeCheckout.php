<?php

namespace Eshop\Controllers\Customer\Checkout\Traits;

use Error;
use Eshop\Actions\Order\SubmitOrder;
use Eshop\Models\Cart\Payment;
use Eshop\Repository\Contracts\Order;
use Eshop\Services\Stripe\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Stripe\Exception\ApiErrorException;
use Throwable;

trait StripeCheckout
{
    protected function checkoutUsingStripe(Request $request, Order $order): JsonResponse
    {
        $stripe = new StripeService();

        try {
            if ($request->filled('payment_method_id')) {
                if (auth()->user()) {
                    $stripe->createCustomer(auth()->user());
                }

                $intent = $stripe->create($order, $request->input('payment_method_id'), auth()->user());
            } elseif ($request->filled('payment_intent_id')) {
                $intent = $stripe->confirm($request->input('payment_intent_id'));
            }

            if (isset($intent)) {
                DB::beginTransaction();
                (new SubmitOrder())->handle($order, auth()->user(), $intent->id, $request->ip());
                $order->payment()->save(new Payment());
                DB::commit();

                return response()->json(URL::signedRoute('checkout.completed', [app()->getLocale(), $order->id]));
            }
            throw new Error();
        } catch (PaymentActionRequired $e) {
            DB::rollBack();
            return response()->json(['requires_action' => true, 'client_secret' => $e->payment->clientSecret()]);
        } catch (ApiErrorException $e) {
            DB::rollBack();
            return response()->json($e->getError()->message, 422);
        } catch (Throwable) {
            DB::rollBack();
            return response()->json(__("Payment was unsuccessful. Please select a different payment method and try again."), 422);
        }
    }
}