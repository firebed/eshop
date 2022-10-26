<?php

namespace Eshop\Services\CourierCenter\Http;

use Eshop\Models\Cart\Cart;
use Eshop\Services\CourierCenter\Exceptions\CourierCenterException;
use Illuminate\Support\Collection;

class CourierCenterCreateVoucher extends CourierCenterRequest
{
    protected string $action = 'Shipment';

    /**
     * @throws CourierCenterException
     */
    public function handle(Collection|Cart $carts): object
    {
        $oneItem = false;
        if ($carts instanceof Cart) {
            $oneItem = true;
            $carts = collect()->add($carts);
        }

        $cart = $carts->first();
        //foreach ($carts as $cart) {
        $voucher = $this->request($this->prepareRequest($cart));
        //}

        return (object)[
            'cart_id' => $cart->id ?? null,
            'number'  => $voucher['ShipmentNumber'] ?? null,
            'success' => filled($voucher['ShipmentNumber'] ?? null),
            //'statusMessage' => $status
        ];

    }

    private function prepareRequest(Cart $cart): array
    {
        $request = array_filter([
            'ShipmentDate' => today()->format('Y-m-d'),
            'Description'  => '',
            'Comments'     => $cart->comments,
            'Consignee'    => array_filter([
                "ContactName" => 'Test client',//$cart->shippingAddress->fullname,
                "Address"     => 'Test St. 123',//$cart->shippingAddress->full_street,
                "City"        => $cart->shippingAddress->city,
                "ZipCode"     => str_replace(' ', '', $cart->shippingAddress->postcode),
                "Country"     => $cart->shippingAddress->country->code,
                "Province"    => $cart->shippingAddress->province,
                "Mobile1"     => '1234567890'//$cart->shippingAddress->phone,
            ]),
            'BillTo'       => 'requestor',
            'Reference 1'  => $cart->id,
            'Items'        => [
                [
                    'Weight' => [
                        'Unit'  => 'kg',
                        'Value' => max(0.5, round($cart->parcel_weight / 1000, 2))
                    ]
                ]
            ],
        ]);

        if ($cart->paymentMethod->isPayOnDelivery()) {
            $request['CODs'] = [
                [
                    'Type'   => 'cash',
                    'Amount' => [
                        'Currency' => 'EUR',
                        'Value'    => $cart->total
                    ]
                ]
            ];
        }

        return $request;
    }
}