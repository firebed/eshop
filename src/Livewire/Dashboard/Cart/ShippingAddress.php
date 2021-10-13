<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\Country;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class ShippingAddress extends Component
{
    use TrimStrings;
    use SendsNotifications;

    public     $shippingAddress;
    public     $email;
    public     $showModal;
    public int $cartId;

    public array $provinces = [];

    protected $rules = [
        'shippingAddress.first_name' => 'required|string',
        'shippingAddress.last_name'  => 'required|string',
        'shippingAddress.phone'      => 'required|string',
        'shippingAddress.country_id' => 'required|integer',
        'shippingAddress.province'   => 'nullable|string',
        'shippingAddress.city'       => 'required|string',
        'shippingAddress.street'     => 'required|string',
        'shippingAddress.street_no'  => 'nullable|string',
        'shippingAddress.postcode'   => 'required|string',
    ];

    public function mount(Cart $cart): void
    {
        $this->cartId = $cart->id;
        $this->email = $cart->email;
        $this->shippingAddress = $cart->shippingAddress()->firstOrNew([], [
            'country_id' => 1
        ]);

        $this->loadProvinces();
    }

    public function edit(): void
    {
        $this->skipRender();
        $this->showModal = true;
    }

    public function updatedShippingAddressCountryId(): void
    {
        $this->loadProvinces();
    }

    private function loadProvinces(): void
    {
        $country = Country::find($this->shippingAddress->country_id);
        $this->provinces = $country?->provinces()->orderBy('name')->pluck('name')->all() ?? [];
    }

    public function save(): void
    {
        $this->validate();
        $this->shippingAddress->province = $this->trim($this->shippingAddress->province);

        $this->shippingAddress->cluster = 'shipping';
        $cart = Cart::find($this->cartId);
        $cart->shippingAddress()->save($this->shippingAddress);
        $this->shippingAddress->save();
        $this->shippingAddress->load('country');

        $this->showSuccessToast('Shipping address saved!');
        $this->showModal = false;
    }

    public function render(): Renderable
    {
        $countries = app('countries');
        $country = null;
        if (isset($this->shippingAddress)) {
            $country = $countries->find($this->shippingAddress->country_id);
        }
        return view('eshop::dashboard.cart.wire.shipping-address', compact('countries', 'country'));
    }
}
