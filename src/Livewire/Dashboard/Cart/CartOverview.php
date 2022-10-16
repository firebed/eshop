<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartChannel;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Repository\Contracts\CartContract;
use Eshop\Services\Stripe\StripeService;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Throwable;

class CartOverview extends Component
{
    use TrimStrings;
    use SendsNotifications;

    public Cart $cart;
    public bool $showEditingModal = false;
    protected   $listeners        = [
        'cart-items-created'          => '$refresh',
        'cart-items-updated'          => '$refresh',
        'cart-items-discount-updated' => '$refresh',
        'cart-items-vat-updated'      => '$refresh',
    ];

    public function edit(): void
    {
        $this->clearErrors();
        $this->showEditingModal = true;
    }

    public function cartVoucherUpdated($voucher): void
    {
        $this->cart->voucher = $voucher;
        $this->skipRender();
    }

    public function markAsPaid(): void
    {
        $this->cart->payment()->updateOrCreate([
            'total' => $this->cart->total
        ]);
    }

    public function markAsUnpaid(): void
    {
        $this->cart->payment()->delete();
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
        $this->showEditingModal = false;
    }

    public function render(StripeService $stripe): Renderable
    {
        $shippingMethods = ShippingMethod::all();
        $paymentMethods = PaymentMethod::all();

        $shippingMethod = $shippingMethods->find($this->cart->shipping_method_id);
        $paymentMethod = $paymentMethods->find($this->cart->payment_method_id);

        if ($this->cart->payment_method_id !== null && $paymentMethod !== null && $paymentMethod->name === 'credit_card') {
            try {
                $cc = $stripe->getCardDetails($this->cart->payment_id);
            } catch (Throwable) {
            }
        }

        $profit = $this->cart->items()->selectRaw("SUM(quantity * (price * (1 - discount) / (1 + vat) - compare_price)) as profits")->first();

        $payment = $this->cart->payment()->first();
        
        return view('eshop::dashboard.cart.wire.cart-overview', [
            'shippingMethods' => $shippingMethods,
            'paymentMethods'  => $paymentMethods,
            'shippingMethod'  => $shippingMethod,
            'paymentMethod'   => $paymentMethod,
            'profit'          => $profit->profits - (eshop('auto_payments') && $payment ? $payment->fees : 0),
            'cc'              => $cc ?? null,
            'payment'         => $payment
        ]);
    }

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

    private function clearErrors(): void
    {
        if ($this->getErrorBag()->isEmpty()) {
            $this->skipRender();
        } else {
            $this->resetValidation();
        }
    }
}
