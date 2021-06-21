<?php


namespace Eshop\Livewire\Customer\Checkout\Concerns;


use Eshop\Repository\Contracts\Order;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Throwable;

trait PayPalCheckout
{
    protected function payWithPayPal(Order $order)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',

            'application_context' => [
                'return_url' => route('checkout.payment.edit', app()->getLocale()),
                'cancel_url' => route('checkout.payment.edit', app()->getLocale()),
            ],

            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value'         => $order->total
                    ],

                    'shipping' => [
                        "address" => [
                            "address_line_1" => $order->shippingAddress->full_street,
                            "admin_area_2"   => $order->shippingAddress->city,
                            "admin_area_1"   => $order->shippingAddress->city,
                            "postal_code"    => $order->shippingAddress->postcode,
                            "country_code"   => $order->shippingAddress->country->code
                        ]
                    ]
                ]
            ]
        ];

        $client = self::client();
        try {
            $response = $client->execute($request);
            return $response->result->id;
        } catch (Throwable) {
            $this->showErrorDialog(__("PayPal Payment"), __("Payment was unsuccessful. Please select a different payment method and try again."));
        }
        return NULL;
    }

    public function confirmPayPalPayment(Order $order, $orderId): ?bool
    {
        if ($this->isCartDirty($order)) {
            return NULL;
        }

        $request = new OrdersCaptureRequest($orderId);

        $client = self::client();
        try {
            $client->execute($request);
            $this->submit($order, $orderId);
        } catch (Throwable) {
            $this->showErrorDialog(__("PayPal Payment"), __("Payment was unsuccessful. Please select a different payment method and try again."));
        }
        return FALSE;
    }

    public static function client(): PayPalHttpClient
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment(): SandboxEnvironment
    {
        $clientId = env("PAYPAL_SANDBOX_CLIENT_ID");
        $clientSecret = env("PAYPAL_SANDBOX_CLIENT_SECRET");
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}
