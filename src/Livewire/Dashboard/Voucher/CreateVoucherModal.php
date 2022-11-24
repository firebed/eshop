<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Models\Cart\Cart;
use Eshop\Services\Courier\Couriers;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CreateVoucherModal extends Component
{
    public bool $showModal = false;

    protected $listeners = [
        'createVoucher'
    ];

    public array $voucher = [
        'courier'            => null,
        'pickup_date'        => null,
        'number_of_packages' => 1,
        'weight'             => 0.5,
        'cod_amount'         => null,
        'customer_name'      => "",
        'address'            => "",
        'address_number'     => "",
        'postcode'           => "",
        'region'             => "",
        'cellphone'          => "",
        'country'            => "",
        'content_type'       => null,
        'services'           => [],
    ];

    public function createVoucher(Cart $cart): void
    {
        $courier = $cart->shippingMethod->courier();

        $this->voucher['reference_1'] = $cart->id;
        $this->voucher['courier'] = $courier->value;
        $this->voucher['pickup_date'] = today()->format('d/m/Y');
        $this->voucher['number_of_packages'] = 1;
        $this->voucher['weight'] = max(round($cart->parcel_weight / 1000, 2), 0.5);
        $this->voucher['cod_amount'] = $cart->paymentMethod->isPayOnDelivery() ? round($cart->total, 2) : null;
        $this->voucher['payment_method'] = $cart->paymentMethod->isPayOnDelivery() ? 1 : null;
        $this->voucher['sender'] = config('app.name');
        $this->voucher['customer_name'] = $cart->shippingAddress->fullName;
        $this->voucher['address'] = $cart->shippingAddress->street;
        $this->voucher['address_number'] = $cart->shippingAddress->street_no;
        $this->voucher['postcode'] = str_replace(" ", "", $cart->shippingAddress->postcode);
        $this->voucher['region'] = $cart->shippingAddress->city;
        $this->voucher['cellphone'] = $cart->shippingAddress->phone;
        $this->voucher['country'] = $cart->shippingAddress->country->code;
        $this->voucher['content_type'] = null;

        $this->loadServices($courier, $cart);

        $this->showModal = true;
    }

    public function purchaseVoucher()
    {
        
    }

    private function loadServices(Couriers $courier, Cart $cart): void
    {
        $this->voucher['services'] = [];

        $this->services = $courier->services($cart->shippingAddress->country->code) ?? [];

        if ($cart->paymentMethod->isPayOnDelivery()) {
            $cod = match ($courier) {
                Couriers::ACS    => 'COD',
                Couriers::GENIKI => 'ΑΜ',
                default          => null,
            };

            if ($cod !== null) {
                $this->voucher['services'][$cod] = $cod;
            }
        }
    }


    public function render(): Renderable
    {
        return view('eshop::dashboard.voucher.wire.create');
    }
}