<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Carbon\Carbon;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Services\Courier\Courier;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Throwable;

class VoucherTableRow extends Component
{
    use SendsNotifications;

    public Cart   $cart;
    public string $number;

    public function mount(Cart $cart)
    {
        $this->cart = $cart;
        $this->number = $cart->voucher()->first()->number ?? "";
    }

    public function createVoucher(Courier $courier)
    {
        $cart = $this->cart;
        if ($cart->voucher()->exists()) {
            return;
        }
        
        $sm = $cart->shippingMethod()->first();

        $query = [
            'charge_type'        => 1,
            'reference_1'        => $cart->id,
            'courier'            => $sm->courier()->value,
            'pickup_date'        => today()->format('Y-m-d'),
            'number_of_packages' => 1,
            'weight'             => max(round($cart->parcel_weight / 1000, 2), 0.5),
            'cod_amount'         => $cart->paymentMethod->isPayOnDelivery() ? round($cart->total, 2) : 0,
            'payment_method'     => $cart->paymentMethod->isPayOnDelivery() ? 1 : null,
            'sender'             => config('app.name'),
            'customer_name'      => $cart->shippingAddress->fullName,
            'address'            => $cart->shippingAddress->street,
            'address_number'     => $cart->shippingAddress->street_no,
            'postcode'           => str_replace(" ", "", $cart->shippingAddress->postcode),
            'region'             => $cart->shippingAddress->city,
            'cellphone'          => $cart->shippingAddress->phone,
            'country'            => $cart->shippingAddress->country->code,
            'content_type'       => null,
        ];

        try {
            $voucher = $courier->createVoucher($query);

            Voucher::create([
                'cart_id'   => $cart->id,
                'courier'   => $query['courier'],
                'number'    => $voucher['number'],
                'is_manual' => false,
                'meta'      => ['uuid' => $voucher['uuid']]
            ]);

            $this->number = $voucher['number'];
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function render(): Renderable
    {
        $voucher = $this->cart->voucher()->first();

        return view('eshop::dashboard.voucher.wire.table-row', [
            'voucher' => $voucher
        ]);
    }
}