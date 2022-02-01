<?php

namespace Eshop\Services;

use Error;
use Eshop\Repository\Contracts\Order;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpResponse;

class PayPalService
{

    public static function client(): PayPalHttpClient
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment(): ProductionEnvironment|SandboxEnvironment
    {
        if (app()->isProduction()) {
            $clientId = config("eshop.paypal_live_client_id");
            $clientSecret = config("eshop.paypal_live_client_secret");
            return new ProductionEnvironment($clientId, $clientSecret);
        }

        $clientId = config("eshop.paypal_sandbox_client_id");
        $clientSecret = config("eshop.paypal_sandbox_client_secret");
        return new SandboxEnvironment($clientId, $clientSecret);
    }

    public function create(Order $order): HttpResponse
    {
        if ($order->shippingAddress === null) {
            throw new Error("The order has no shipping address");
        }

        $currency = eshop('currency');

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
                    // 'reference_id'    => 'PUHF',
                    // 'description'     => 'Sporting Goods',
                    // 'custom_id'       => 'CUST-HighFashions',
                    // 'soft_descriptor' => 'HighFashions',
                    'amount' => [
                        'currency_code' => $currency,
                        'value'         => $order->total,
                        'breakdown'     => [
                            'item_total' => [
                                'currency_code' => $currency,
                                'value'         => $order->products_value,
                            ],
                            'shipping'   => [
                                'currency_code' => $currency,
                                'value'         => $order->shipping_fee
                            ],
                            'handling'   => [
                                'currency_code' => $currency,
                                'value'         => $order->payment_fee,
                            ],
                            //                            'tax_total'         => [
                            //
                            //                                'currency_code' => $currency,
                            //                                'value'         => '20.00',
                            //                            ],
                            //                            'shipping_discount' => [
                            //                                'currency_code' => $currency,
                            //                                'value'         => '10.00',
                            //                            ],
                        ],
                    ],

                    'items'    => $this->items($order, $currency),
                    'shipping' => $this->shipping($order)
                ]
            ]
        ];

        return self::client()->execute($request);
    }

    public function capture(string $orderId): HttpResponse
    {
        $client = self::client();

        $request = new OrdersCaptureRequest($orderId);
        return $client->execute($request);
    }

    private function shipping(Order $order): array
    {
        return [
            'method'  => trans('eshop::shipping.' . $order->shippingMethod->name),
            "address" => [
                "address_line_1" => $order->shippingAddress->full_street,
                "admin_area_2"   => $order->shippingAddress->city,
                "admin_area_1"   => $order->shippingAddress->city,
                "postal_code"    => $order->shippingAddress->postcode,
                "country_code"   => $order->shippingAddress->country->code
            ]
        ];
    }

    private function items(Order $order, $currency): array
    {
        $order->loadMissing('products.parent.translation', 'products.translation', 'products.options');
        $items = [];
        foreach ($order->products as $product) {
            $items[] = [
                'name'        => $product->trademark,
                //                'description' => $product->description,
                'sku'         => $product->sku,
                'unit_amount' => [
                    'currency_code' => $currency,
                    'value'         => $product->pivot->net_value
                ],
                'quantity'    => $product->pivot->quantity,
                //                'category' => 'PHYSICAL_GOODS',
            ];
        }
        return $items;
    }
}