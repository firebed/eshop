<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class ShippingAddress extends Component
{
    use TrimStrings;
    use SendsNotifications;

    public $shippingAddress;
    public $showModal;

    protected $rules = [
        'shippingAddress.first_name' => 'required|string',
        'shippingAddress.last_name'  => 'required|string',
        'shippingAddress.phone'      => 'required|string',
        'shippingAddress.country_id' => 'required|integer',
        'shippingAddress.province'   => 'nullable|string',
        'shippingAddress.city'       => 'required|string',
        'shippingAddress.street'     => 'required|string',
        'shippingAddress.postcode'   => 'required|string',
    ];

    public function mount(Cart $cart): void
    {
        $this->shippingAddress = $cart->shippingAddress()->first();
    }

    public function edit(): void
    {
        $this->skipRender();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $this->shippingAddress->province = $this->trim($this->shippingAddress->province);

        $this->shippingAddress->save();
        $this->shippingAddress->load('country');

        $this->showSuccessToast('Shipping address saved!');
        $this->showModal = false;
    }

    public function render(): Renderable
    {
        $countries = app('countries');
        $country = $countries->find($this->shippingAddress->country_id);
        return view('eshop::dashboard.cart.livewire.shipping-address', compact('countries', 'country'));
    }
}
