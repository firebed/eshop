<?php

namespace Eshop\Livewire\Customer\Checkout\Concerns;


use Eshop\Models\User\User;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Throwable;

trait StripeCheckout
{
    public ?string $intent;
    public ?string $clientSecret;

    public function chargeStripeCard(Order $order, $paymentMethodId): void
    {
        if ($this->orderIsSubmitted()) {
            return;
        }

        $this->intent = null;

        if (Auth::check()) {
            auth()->user()?->createOrGetStripeCustomer([
                'name'     => auth()->user()->full_name,
                'email'    => auth()->user()->email,
                'metadata' => [
                    'id' => auth()->user()->id
                ]
            ]);
        }

        $amount = $order->total * 100;

        try {
            $payment = $this->user()->charge($amount, $paymentMethodId, [
                'confirmation_method' => 'manual',
                'metadata'            => [
                    'order_id'       => $order->id,
                    'customer_notes' => $order->details
                ],
                'receipt_email'       => $order->email,
                'shipping'            => [
                    'name'            => $order->shippingAddress->full_name,
                    'carrier'         => $order->shippingMethod->name,
                    'address'         => [
                        'line1'       => $order->shippingAddress->full_street,
                        'city'        => $order->shippingAddress->city,
                        'country'     => $order->shippingAddress->country->code,
                        'postal_code' => $order->shippingAddress->postcode,
                        'state'       => $order->shippingAddress->province,
                    ],
                    'tracking_number' => '',
                ]
            ]);

            $this->success($payment->asStripePaymentIntent(), $order);
        } catch (PaymentActionRequired $e) {
            $this->paymentActionRequired($e);
        } catch (Throwable $e) {
            $this->error($e);
        }
    }

    public function confirmStripePayment(Order $order): void
    {
        if ($this->isCartDirty($order)) {
            return;
        }

        try {
            $intent = PaymentIntent::retrieve($this->intent, Cashier::stripeOptions());
            $intent->confirm();
            $this->success($intent, $order);
        } catch (ApiErrorException $e) {
            $this->error($e);
        }
    }

    protected function payWithStripe(): void
    {
        $this->dispatchBrowserEvent('stripe-charge-card');
    }

    private function paymentActionRequired(PaymentActionRequired $e): void
    {
        $this->intent = $e->payment->id;

        $this->dispatchBrowserEvent('stripe-payment-action-required', $e->payment->clientSecret());
    }

    private function success(PaymentIntent $payment, Order $order): void
    {
        $this->submit($order, $payment->id);
    }

    private function error(Throwable $e): void
    {
        $this->dispatchBrowserEvent('stripe-checkout-error', __($e->getMessage()));
    }

    private function user(): Authenticatable
    {
        return auth()->user() ?? new User();
    }

    private function getCardDetails(PaymentIntent $payment)
    {
        $card = $payment->charges->data[0]['payment_method_details']->card;
//        "brand" => "visa"
//    "checks" => array:3 [
//        "address_line1_check" => "pass"
//      "address_postal_code_check" => "pass"
//      "cvc_check" => "pass"
//    ]
//    "country" => "DE"
//    "exp_month" => 12
//    "exp_year" => 2024
//    "fingerprint" => "qiVvCPxKowTvU4ar"
//    "funding" => "credit"
//    "installments" => null
//    "last4" => "3184"
//    "network" => "visa"
//    "three_d_secure" => array:6 [
//        "authenticated" => true
//      "authentication_flow" => "challenge"
//      "result" => "authenticated"
//      "result_reason" => null
//      "succeeded" => true
//      "version" => "1.0.2"
//    ]
    }
}
