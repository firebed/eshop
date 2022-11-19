<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Actions\Order\SubmitOrder;
use Eshop\Controllers\Customer\Checkout\Traits\PayPalCheckout;
use Eshop\Controllers\Customer\Checkout\Traits\SimplifyCheckout;
use Eshop\Controllers\Customer\Checkout\Traits\StripeCheckout;
use Eshop\Controllers\Customer\Checkout\Traits\ValidatesCheckout;
use Eshop\Controllers\Customer\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Cart\CartEvent;
use Eshop\Models\Cart\Payment;
use Eshop\Repository\Contracts\Order;
use Eshop\Requests\Customer\CheckoutPaymentRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class CheckoutPaymentController extends Controller
{
    use WithNotifications;
    use ValidatesCheckout;
    use StripeCheckout, PayPalCheckout, SimplifyCheckout;

    public function store(string $lang, Request $request, Order $order): RedirectResponse|JsonResponse
    {
        if (panicking()) {
            session()->flash('safe');
            return redirect()->route('checkout.payment.edit', $lang);
        }
        
        if (blank($order->shipping_method_id)) {
            throw ValidationException::withMessages(['shipping_method_error' => '']);
        }

        if (blank($order->payment_method_id)) {
            throw ValidationException::withMessages(['payment_method_error' => '']);
        }
                
        if (!$this->validateCheckout($order)) {
            return redirect()->route('checkout.products.index', $lang);
        }
        
        if ($this->processingFeesHasChanged()) {
            session()->flash('processing-fees-changed');
            return redirect()->route('checkout.payment.edit', $lang);            
        }

        if (!$this->validateShippingAddress($order)) {
            return redirect()->route('checkout.details.edit', app()->getLocale());
        }

        if ($order->paymentMethod->isPayPal()) {
            return $this->checkoutUsingPayPal($request, $order);
        }

        if ($order->paymentMethod->isCreditCard()) {
            return $this->checkoutUsingStripe($request, $order);
        }

        if ($order->paymentMethod->isCreditCardSimplify()) {
            return $this->checkoutUsingSimplify($request, $order);
        }

        DB::beginTransaction();
        (new SubmitOrder())->handle($order, auth()->user(), ip: $request->ip());
        if (!$order->paymentMethod->isPayOnDelivery()) {
            $order->payment()->save(new Payment([
                'total' => $order->total
            ]));
        }
        DB::commit();

        return redirect()->to(URL::signedRoute('checkout.completed', [app()->getLocale(), $order->id]));
    }

    public function edit(string $lang, Order $order, ShippingFeeCalculator $calc): Renderable|RedirectResponse
    {
        if (!$this->validateCheckout($order)) {
            return redirect()->route('checkout.products.index', $lang);
        }

        if (!$this->validateShippingAddress($order)) {
            return redirect()->route('checkout.details.edit', app()->getLocale());
        }

        CartEvent::getCheckoutPayment($order->id);
        
        $country = $order->shippingAddress->country;
        $shippingOptions = $country->filterShippingOptions($order->products_value);
        $paymentOptions = $country->filterPaymentOptions($order->products_value);

        Collection::make($shippingOptions)->load('translations', 'shippingMethod');
        Collection::make($paymentOptions)->load('translations', 'paymentMethod');

        foreach ($shippingOptions as $shippingMethod) {
            $shippingMethod->total_fee = $calc->handle($shippingMethod, $order->parcel_weight, $order->shippingAddress->postcode);
            $area = $shippingMethod->shippingMethod->inaccessibleAreas()->firstWhere('postcode', $order->shippingAddress->postcode);
            if ($area?->type !== null) {
                $shippingMethod->setRelation('area', $area);
            }
        }

        $shippingOptions = $shippingOptions->reject(fn($method) => $method->area === null && $method->inaccessible_area_fee > 0);

        $products = $order->products;
        $products->load('parent', 'variantOptions.translations');
        $products->merge($order->products->pluck('parent')->filter())->load('translations');

        return $this->view('checkout.payment.edit', [
            'order'                      => $order,
            'shippingMethods'            => $shippingOptions,
            'country_shipping_method_id' => session('countryShippingMethod'),
            'paymentMethods'             => $paymentOptions,
            'country_payment_method_id'  => session('countryPaymentMethod'),
            'products'                   => $products,
        ]);
    }

    public function update(CheckoutPaymentRequest $request, Order $order, RefreshOrder $refreshOrder): JsonResponse
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return response()->json('', 422);
        }

        if ($request->filled('country_shipping_method_id')) {
            session()->put('countryShippingMethod', $request->input('country_shipping_method_id'));
        }

        if ($request->filled('country_payment_method_id')) {
            session()->put('countryPaymentMethod', $request->input('country_payment_method_id'));
        }

        DB::transaction(static fn() => $refreshOrder->handle($order));

        $products = $order->products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translations');

        return response()
            ->json($this->view('checkout.payment.partials.checkout-payment-summary', [
                'order'    => $order,
                'products' => $products,
            ])->render());
    }
}
