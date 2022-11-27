<?php

namespace Eshop\Actions;

use Carbon\Carbon;
use Eshop\Models\Cart\Cart;

class CreateVoucherRequest
{
    public function handle(Cart $cart, int $number_of_packages = 1, Carbon $pickup_date = null, string $content_type = null): array
    {
        $pickup_date ??= today();

        return [
            'charge_type'        => 1,
            'reference_1'        => $cart->id,
            'pickup_date'        => $pickup_date->format('Y-m-d'),
            'number_of_packages' => $number_of_packages,
            'weight'             => max(round($cart->parcel_weight / 1000, 2), 0.5),
            'cod_amount'         => $cart->paymentMethod->isPayOnDelivery() ? round($cart->total, 2) : null,
            'payment_method'     => $cart->paymentMethod->isPayOnDelivery() ? 1 : null,
            'sender_name'        => config('app.name'),
            'customer_name'      => $cart->shippingAddress->fullName,
            'address'            => $cart->shippingAddress->street,
            'address_number'     => $cart->shippingAddress->street_no,
            'postcode'           => str_replace(" ", "", $cart->shippingAddress->postcode),
            'region'             => $cart->shippingAddress->city,
            'cellphone'          => $cart->shippingAddress->phone,
            'country'            => $cart->shippingAddress->country->code,
            'content_type'       => $content_type,
            'services'           => []
        ];
    }
}