<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Repository\Contracts\CartContract;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ManagesVoucher
{
    private Collection $vouchers;
    public ?Voucher    $editingVoucher = null;

    public bool $showVoucherModal = false;

    public function buyShippingLabel(): void
    {
        $cart = Cart::find($this->cart_id);
        Voucher::create([
            'cart_id'            => $cart->id,
            'shipping_method_id' => $cart->shipping_method_id,
            'number'             => random_int(1000000000, 9999999999),
            'is_manual'          => false
        ]);
    }

    public function createVoucher(): void
    {
        $cart = Cart::find($this->cart_id);
        $this->editingVoucher = new Voucher(['shipping_method_id' => $cart->shipping_method_id]);
        $this->showVoucherModal = true;
    }

    public function editVoucher(Voucher $voucher): void
    {
        $this->editingVoucher = $voucher;
        $this->showVoucherModal = true;
    }

    public function printVoucher(Voucher $voucher): ?StreamedResponse
    {
        $shippingMethod = $voucher->shippingMethod;
        $pdf = $shippingMethod->printVoucher($voucher->number);

        if (blank($pdf)) {
            $this->showErrorToast("Αποτυχία σύνδεσης");
            $this->skipRender();
            return null;
        }

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $voucher->number . '.pdf', ['ContentType' => 'application/pdf']);
    }

    public function saveVoucher(CartContract $contract): void
    {
        $this->validate();

        if ($this->editingVoucher && $this->editingVoucher->exists) {
            $this->editingVoucher->save();
            $this->showVoucherModal = false;
            return;
        }

        $cart = Cart::find($this->cart_id);
        $number = trim($this->editingVoucher->number) ?: null;
        if ($contract->setVoucher($cart->id, $number, $this->editingVoucher->shipping_method_id, true)) {
            $this->showVoucherModal = false;

            $this->showSuccessToast('Voucher saved!');
        } else {
            $this->addError('voucher', 'An error occurred. The voucher code was not updated.');
        }
    }

    public function cancelVoucher(Voucher $voucher): void
    {
        $shippingMethod = $voucher->shippingMethod;
        $result = $shippingMethod->cancelVoucher($voucher->number);
        if ($result === true) {
            $voucher->update(['cancelled_at' => now()]);
            $this->showSuccessToast("Ο κωδικός αποστολής $voucher->number ακυρώθηκε.");
        } else {
            $this->showErrorToast($result);
        }
    }

    public function deleteVoucher(Voucher $voucher): void
    {
        $voucher->delete();
    }

    public function renderingManagesVoucher(): void
    {
        $this->vouchers = Voucher::where('cart_id', $this->cart_id)->latest()->get();
    }
}
