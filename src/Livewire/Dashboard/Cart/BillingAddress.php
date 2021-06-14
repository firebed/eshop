<?php


namespace Eshop\Livewire\Dashboard\Cart;


use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class BillingAddress extends Component
{
    use SendsNotifications;
    use TrimStrings;

    public $billingAddress;
    public $sameAsShipping;
    public $showModal = false;

    protected $rules = [
        'billingAddress.id'               => 'nullable|integer',
        'billingAddress.addressable_id'   => 'required|integer',
        'billingAddress.addressable_type' => 'required|string',
        'billingAddress.cluster'          => 'required|string',
        'billingAddress.country_id'       => 'required_if:sameAsShipping,false|integer',
        'billingAddress.province'         => 'nullable|string',
        'billingAddress.city'             => 'required_if:sameAsShipping,false|string',
        'billingAddress.street'           => 'required_if:sameAsShipping,false|string',
        'billingAddress.postcode'         => 'required_if:sameAsShipping,false|string',
    ];

    public function mount(Cart $cart): void
    {
        $this->billingAddress = $cart->billingAddress()->firstOrNew([
            'cluster' => 'billing',
        ], [
            'country_id' => ''
        ]);

        if (empty($this->billingAddress->id)) {
            $this->sameAsShipping = true;
        }
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->sameAsShipping) {
            $this->billingAddress->province = $this->trim($this->billingAddress->province);
            $this->billingAddress->save();
            $this->billingAddress->load('country');
        } elseif ($this->billingAddress->getKey()) {
            $this->billingAddress->delete();
            $this->billingAddress->id = null;
        }

        $this->showModal = false;
        $this->showSuccessToast('Billing address saved!');
    }

    public function render(): Renderable
    {
        $countries = app('countries');
        $country = $countries->find($this->billingAddress->country_id);
        return view('eshop::dashboard.cart.wire.billing-address', compact('countries', 'country'));
    }
}
