<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CustomerNotes extends Component
{
    use TrimStrings;
    use SendsNotifications;

    public $cart_id;
    public $notes;
    public $showModal;

    protected $rules = [
        'notes' => 'nullable|string',
    ];

    public function mount(Cart $cart): void
    {
        $this->cart_id = $cart->id;
        $this->notes = $cart->details;
    }

    public function save(): void
    {
        $this->validate();

        Cart::where('id', $this->cart_id)->update([
            'details' => $this->trim($this->notes)
        ]);

        $this->showSuccessToast('Customer notes saved!');
        $this->showModal = false;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.cart.wire.customer-notes');
    }
}
