<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\Country;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Stevebauman\Location\Facades\Location;

class ShippingAddress extends Component
{
    use TrimStrings;
    use SendsNotifications;

    public     $shippingAddress;
    public     $email;
    public     $showModal;
    public int $cartId;
    public     $user_id;
    public     $ip;

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
        $this->ip = $cart->ip;
        $this->user_id = $cart->user_id;
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

    public function save(): void
    {
        $this->validate();
        $this->validate(['email' => ['required', 'email:dns,rfc']]);

        $this->shippingAddress->province = $this->trim($this->shippingAddress->province);

        DB::transaction(function () {
            $this->shippingAddress->cluster = 'shipping';
            $cart = Cart::find($this->cartId);
            $cart->shippingAddress()->save($this->shippingAddress);
            $this->shippingAddress->save();
            $this->shippingAddress->load('country');

            Cart::whereKey($this->cartId)->update(['email' => trim($this->email)]);

            event('eloquent.updated: ' . get_class($cart), $cart);

            $this->showSuccessToast('Shipping address saved!');
            $this->showModal = false;
        });
    }

    public function render(): Renderable
    {
        $countries = app('countries');
        $country = null;
        if (isset($this->shippingAddress)) {
            $country = $countries->find($this->shippingAddress->country_id);
        }
        return view('eshop::dashboard.cart.wire.shipping-address', [
            'countries' => $countries,
            'country'   => $country,
            //'location'  => empty($this->ip) ? null : Location::get($this->ip)
        ]);
    }

    private function loadProvinces(): void
    {
        $country = Country::find($this->shippingAddress->country_id);
        $this->provinces = $country?->provinces()->orderBy('name')->pluck('name')->all() ?? [];
    }
}
