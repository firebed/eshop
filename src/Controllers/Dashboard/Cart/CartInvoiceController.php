<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Invoice\Client;
use Eshop\Models\Invoice\InvoiceType;
use Eshop\Models\Invoice\PaymentMethod;
use Eshop\Models\Invoice\UnitMeasurement;
use Eshop\Models\Location\PaymentMethod as CartPaymentMethod;
use Eshop\Models\Product\Unit;
use Illuminate\Http\RedirectResponse;

class CartInvoiceController extends Controller
{
    public function __invoke(Cart $cart): string|RedirectResponse
    {
        if (!$cart->isDocumentInvoice()) {
            return "<script>window.close();</script>";
        }

        if ($cart->invoice === null || $cart->invoice->billingAddress === null) {
            return "<script>window.close();</script>";
        }

        $invoice = $cart->invoice;

        $client = Client::firstWhere('vat_number', $invoice->vat_number);

        if ($client === null) {
            session()->flashInput([
                'name'          => $invoice->name,
                'job'           => $invoice->job,
                'vat_number'    => $invoice->vat_number,
                'tax_authority' => $invoice->tax_authority,
                'country'       => $invoice->billingAddress->country->code,
                'city'          => $invoice->billingAddress->city,
                'street'        => $invoice->billingAddress->street,
                'street_number' => $invoice->billingAddress->street_no,
                'postcode'      => $invoice->billingAddress->postcode,
                'phone_number'  => $invoice->billingAddress->phone,
            ]);

            return redirect()->route('clients.create', ['cart_id' => $cart->id]);
        }

        $cart->products->load('translation', 'parent.translation', 'unit', 'variantOptions.translation');

        $rows = [];
        foreach ($cart->products as $product) {
            $rows[] = [
                'id'          => '',
                'code'        => $product->sku,
                'description' => $product->trademark,
                'unit'        => $this->unit($product->unit)->value,
                'quantity'    => $product->pivot->quantity,
                'price'       => round($product->pivot->price / (1 + $product->pivot->vat), 4),
                'discount'    => $product->pivot->discount,
                'vat_percent' => $client->country === 'GR' ? $product->pivot->vat : 0,
            ];
        }

        if ($cart->shipping_fee > 0) {
            $rows[] = [
                'id'          => '',
                'code'        => 'SHP',
                'description' => 'Μεταφορικά έξοδα (' . __("eshop::shipping." . $cart->shippingMethod->name) . ')',
                'unit'        => UnitMeasurement::Pieces->value,
                'quantity'    => 1,
                'price'       => round($cart->shipping_fee / (1 + 0.24), 4),
                'discount'    => 0,
                'vat_percent' => $client->country === 'GR' ? 0.24 : 0,
            ];
        }

        if ($cart->payment_fee > 0) {
            $rows[] = [
                'id'          => '',
                'code'        => 'PYM',
                'description' => 'Έξοδα πληρωμής (' . __("eshop::payment." . $cart->paymentMethod->name) . ')',
                'unit'        => UnitMeasurement::Pieces->value,
                'quantity'    => 1,
                'price'       => round($cart->payment_fee / (1 + 0.24), 4),
                'discount'    => 0,
                'vat_percent' => $client->country === 'GR' ? 0.24 : 0
            ];
        }

        session()->flashInput([
            'type'                => InvoiceType::TPA->value,
            'client_id'           => $client->id,
            'payment_method'      => $this->paymentMethod($cart->paymentMethod)->value ?? null,
            'relative_document'   => 'Παραγγελία #' . $cart->id,
            'transaction_purpose' => 'Πώληση',
            'rows'                => $rows
        ]);

        $clientName = $client->name . " ($client->vat_number)";
        session()->flash('client', $clientName);

        return redirect()->route('invoices.create');
    }

    private function paymentMethod(CartPaymentMethod $cpm): ?PaymentMethod
    {
        return match ($cpm->name) {
            'paypal'           => PaymentMethod::PayPal,
            'credit_card'      => PaymentMethod::CreditCard,
            'wire_transfer'    => PaymentMethod::WireTransfer,
            'pay_on_delivery'  => PaymentMethod::POD,
            'pay_in_our_store' => PaymentMethod::Cash,
            default            => null
        };
    }

    private function unit(Unit $unit): UnitMeasurement
    {
        return match ($unit->name) {
            'piece'    => UnitMeasurement::Pieces,
            'meter'    => UnitMeasurement::Meters,
            'liter'    => UnitMeasurement::Liters,
            'kilogram' => UnitMeasurement::Kilos,
            'set'      => UnitMeasurement::Set
        };
    }
}
