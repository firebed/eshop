<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartInvoice;
use Eshop\Models\Cart\CartStatus;
use Eshop\Models\Cart\DocumentType;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Models\Product\Product;

class CreateOrder
{
    public function handle($event): void
    {
        $order = $event['order'];

        $items = collect($order['line_items']);
        $products = collect();
        $weight = 0;
        foreach ($items as $item) {
            if (isset($item['size'])) {
                $product = Product::find($item['size']['shop_variation_uid']);
            } else {
                $product = Product::find($item['shop_uid']);
            }

            $weight += $item['quantity'] * $product->weight;

            $vat = $product->vat;
            if ($item['island_vat_discount_applied']) {
                $vat = round($vat * 0.7, 2); // 30% off for islands
            }
            
            $products->put($product->id, [
                'quantity'      => $item['quantity'],
                'price'         => $item['unit_price'],
                'compare_price' => $product->compare_price,
                'discount'      => 0,
                'vat'           => $vat
            ]);
            
            $product->stock -= $item['quantity'];
            $product->save();
        }

        $bankTransfer = PaymentMethod::firstWhere('name', 'wire_transfer');

        if ($order['courier'] === 'ACS') {
            $acs = ShippingMethod::firstWhere('name', 'ACS Courier');
        }

        $status = CartStatus::firstWhere('name', CartStatus::SUBMITTED);

        $cart = new Cart();
        $cart->channel = 'skroutz';
        $cart->reference_id = $order['code'];
        $cart->paymentMethod()->associate($bankTransfer);
        $cart->shippingMethod()->associate($acs ?? null);
        $cart->status()->associate($status);
        $cart->document_type = $order['invoice'] ? DocumentType::INVOICE : DocumentType::RECEIPT;
        $cart->parcel_weight = $weight;
        $cart->total = $items->sum('total_price');
        $cart->details = $order['comments'];
        $cart->gift_wrap = $order['gift_wrap'];
        $cart->created_at = $order['created_at'];
        $cart->updated_at = $order['created_at'];
        $cart->submitted_at = $order['created_at'];
        $cart->save();

        $cart->products()->sync($products);

        $customer = $order['customer'];
        $address = $customer['address'];
        $country = Country::firstWhere('code', $address['country_code']);
        $cart->shippingAddress()->save(new Address([
            'cluster'    => 'shipping',
            'country_id' => $country->id,
            'first_name' => $customer['first_name'],
            'last_name'  => $customer['last_name'],
            'city'       => $address['city'],
            'street'     => $address['street_name'],
            'street_no'  => $address['street_number'],
            'postcode'   => $address['zip'],
        ]));

        if ($order['invoice']) {
            $details = $order['invoice_details'];
            $address = $details['address'];

            $invoice = new CartInvoice();
            $invoice->name = $details['company'];
            $invoice->job = $details['profession'];
            $invoice->vat_number = $details['vat_number'];
            $invoice->tax_authority = $details['doy'];
            $cart->invoice()->save($invoice);

            $invoice->billingAddress()->save(new Address([
                'cluster'    => 'billing',
                'country_id' => $country->id,
                'city'       => $address['city'],
                'street'     => $address['street_name'],
                'street_no'  => $address['street_number'],
                'postcode'   => $address['zip'],
            ]));
        }
    }
}