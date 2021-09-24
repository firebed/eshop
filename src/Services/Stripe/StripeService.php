<?php

namespace Eshop\Services\Stripe;

use Eshop\Models\Cart\Cart;
use Eshop\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Laravel\Cashier\Exceptions\PaymentFailure;
use Laravel\Cashier\Payment;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class StripeService
{
    public function createCustomer(Authenticatable $user): Customer
    {
        return $user->createOrGetStripeCustomer([
            'name'     => auth()->user()->full_name,
            'email'    => auth()->user()->email,
            'metadata' => [
                'id' => auth()->id()
            ]
        ]);

    }

    /**
     * @throws PaymentFailure
     * @throws PaymentActionRequired
     */
    public function create(Cart $order, string $payment_method_id, ?Authenticatable $user): Payment
    {
        $amount = $order->total * 100;

        $user = $user ?? new User();

        return $user->charge($amount, $payment_method_id, [
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
    }

    /**
     * @throws ApiErrorException
     */
    public function confirm(string $payment_intent_id): PaymentIntent
    {
        $intent = $this->retrievePaymentIntent($payment_intent_id);
        return $intent->confirm();
    }

    /**
     * @throws ApiErrorException
     */
    public function getCardDetails(string $payment_intent_id): StripeCard
    {
        $intent = $this->retrievePaymentIntent($payment_intent_id);
        return new StripeCard($intent);
    }

    /**
     * @throws ApiErrorException
     */
    private function retrievePaymentIntent(string $payment_intent_id): PaymentIntent
    {
        return PaymentIntent::retrieve($payment_intent_id, Cashier::stripeOptions());
    }
}