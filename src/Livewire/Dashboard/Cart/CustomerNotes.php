<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CustomerNotes extends Component
{
    use TrimStrings;
    use SendsNotifications;

    public $cart_id;
    public $notes;
    public $comments;
    public $showModal;

    protected $rules = [
        'notes'    => 'nullable|string',
        'comments' => 'nullable|string',
    ];

    public function mount(Cart $cart): void
    {
        $this->cart_id = $cart->id;
        $this->notes = $cart->details;
        $this->comments = $cart->comments;
    }

    public function save(): void
    {
        $this->validate();

        $cart = Cart::find($this->cart_id);
        $cart->update([
            'details'  => $this->trim($this->notes),
            'comments' => $this->trim($this->comments)
        ]);

        $this->showSuccessToast('Customer notes saved!');
        $this->showModal = false;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.cart.wire.customer-notes');
    }
}
