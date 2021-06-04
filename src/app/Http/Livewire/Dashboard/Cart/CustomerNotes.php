<?php

namespace App\Http\Livewire\Dashboard\Cart;

use App\Http\Livewire\Traits\TrimStrings;
use App\Models\Cart\Cart;
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
        return view('dashboard.cart.livewire.customer-notes');
    }
}
