<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartChannel;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Repository\Contracts\CartContract;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CartOverview extends Component
{
    use TrimStrings;
    use SendsNotifications;

    public Cart $cart;
    public bool $showEditingModal = FALSE;

    protected function rules(): array
    {
        return [
            'cart.shipping_method_id' => 'required|integer',
            'cart.shipping_fee'       => 'required|numeric',
            'cart.payment_method_id'  => 'required|integer',
            'cart.payment_fee'        => 'required|numeric',
            'cart.document_type'      => 'required|string',
            'cart.channel'            => 'required|string|in:' . implode(',', CartChannel::all()),
        ];
    }

    protected $listeners = [
        'cart-items-created'          => '$refresh',
        'cart-items-updated'          => '$refresh',
        'cart-items-discount-updated' => '$refresh',
    ];

    public function edit(): void
    {
        $this->clearErrors();
        $this->showEditingModal = TRUE;
    }

    private function clearErrors(): void
    {
        if ($this->getErrorBag()->isEmpty()) {
            $this->skipRender();
        } else {
            $this->resetValidation();
        }
    }

    public function cartVoucherUpdated($voucher): void
    {
        $this->cart->voucher = $voucher;
        $this->skipRender();
    }

    public function save(CartContract $contract): void
    {
        $this->validate();

        $docTypeUpdated = $this->cart->isDirty('document_type');

        $contract->updateCart($this->cart);

        if ($docTypeUpdated) {
            $this->emit('cartDocumentUpdated', $this->cart->document_type);
        }

        $this->showSuccessToast('Cart updated!');
        $this->showEditingModal = FALSE;
    }

    public function render(): Renderable
    {
        $shippingMethods = ShippingMethod::all();
        $paymentMethods = PaymentMethod::all();

        $shippingMethod = $shippingMethods->find($this->cart->shipping_method_id);
        $paymentMethod = $paymentMethods->find($this->cart->payment_method_id);

        return view('eshop::dashboard.cart.wire.cart-overview', compact('shippingMethods', 'paymentMethods', 'shippingMethod', 'paymentMethod'));
    }
}
