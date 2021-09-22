<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Requests\CheckoutPaymentRequest;
use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Actions\Order\SubmitOrder;
use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Repository\Contracts\Order;
use Eshop\Services\PayPalService;
use Eshop\Services\Stripe\StripeService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Stripe\Exception\ApiErrorException;
use Throwable;

class CheckoutPaymentController extends Controller
{
    use WithNotifications;

    public function store(Request $request, Order $order, RefreshOrder $refreshOrder, PayPalService $paypal, StripeService $stripe, SubmitOrder $submit): RedirectResponse|JsonResponse
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return redirect()->route('checkout.products.index', app()->getLocale());
        }

        $refreshOrder->handle($order);

        if ($order->paymentMethod->isPayPal()) {
            try {
                if ($request->missing('order_id')) {
                    $response = $paypal->create($order);
                    return response()->json($response->result->id);
                }

                $orderId = $request->input('order_id');

                DB::beginTransaction();
                $submit->handle($order, auth()->user(), $orderId, $request->ip());
                $paypal->capture($orderId);
                DB::commit();
                return response()->json(URL::signedRoute('checkout.completed', [app()->getLocale(), $order->id]));
            } catch (Throwable) {
                DB::rollBack();
                return response()->json(__("Payment was unsuccessful. Please select a different payment method and try again."), 422);
            }
        }

        if ($order->paymentMethod->isCreditCard()) {
            try {
                $intent = null;

                if ($request->filled('payment_method_id')) {
                    if (auth()->user()) {
                        $stripe->createCustomer(auth()->user());
                    }

                    $intent = $stripe->create($order, $request->input('payment_method_id'), auth()->user());
                } elseif ($request->filled('payment_intent_id')) {
                    $intent = $stripe->confirm($request->input('payment_intent_id'));
                }

                if ($intent) {
                    DB::beginTransaction();
                    $submit->handle($order, auth()->user(), $intent->id, $request->ip());
                    DB::commit();

                    return response()->json(URL::signedRoute('checkout.completed', [app()->getLocale(), $order->id]));
                }
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

        DB::beginTransaction();
        $submit->handle($order, auth()->user(), ip: $request->ip());
        DB::commit();

        return redirect()->to(URL::signedRoute('checkout.completed', [app()->getLocale(), $order->id]));
    }

    public function edit(Order $order, RefreshOrder $refreshOrder, ShippingFeeCalculator $calc): Renderable|RedirectResponse
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return redirect()->route('checkout.products.index', app()->getLocale());
        }

        $country = $order->shippingAddress->country;
        $shippingMethods = $country->filterShippingOptions($order->products_value);
        $paymentMethods = $country->filterPaymentOptions($order->products_value);

        Collection::make($shippingMethods)->load('translation', 'shippingMethod');
        Collection::make($paymentMethods)->load('translation', 'paymentMethod');

        foreach ($shippingMethods as $shippingMethod) {
            $shippingMethod->total_fee = $calc->handle($shippingMethod, $order->parcel_weight, $order->shippingAddress->postcode);
            $area = $shippingMethod->shippingMethod->inaccessibleAreas()->firstWhere('postcode', $order->shippingAddress->postcode);
            if ($area?->type !== null) {
                $shippingMethod->setRelation('area', $area);
            }
        }

        $shippingMethods = $shippingMethods->reject(fn($method) => $method->area === null && $method->inaccessible_area_fee > 0);

        $refreshOrder->handle($order);

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        return view('checkout.payment.edit', [
            'order'           => $order,
            'shippingMethods' => $shippingMethods,
            'paymentMethods'  => $paymentMethods,
            'products'        => $products,
        ]);
    }

    public function update(CheckoutPaymentRequest $request, Order $order, RefreshOrder $refreshOrder): JsonResponse
    {
        if ($request->filled('country_shipping_method_id')) {
            $order->shippingMethod()->associate($request->input('country_shipping_method_id'));
        }

        if ($request->filled('country_payment_method_id')) {
            $order->paymentMethod()->associate($request->input('country_payment_method_id'));
        }

        $order->save();
        $refreshOrder->handle($order);

        $products = $order->products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        return response()
            ->json(view('checkout.payment.partials.checkout-payment-summary', [
                'order'    => $order,
                'products' => $products,
            ])->render());
    }
}
