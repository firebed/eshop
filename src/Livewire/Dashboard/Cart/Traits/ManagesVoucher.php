<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Repository\Contracts\CartContract;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

trait ManagesVoucher
{
    private Collection $vouchers;
    public ?Voucher    $editingVoucher = null;

    public bool $showVoucherModal = false;

    public function buyShippingLabel(): void
    {
        $cart = Cart::find($this->cart_id);
        try {
            $voucher = $cart->shippingMethod->createVoucher($cart);

            if ($voucher->success) {
                Voucher::create([
                    'cart_id'            => $voucher->cart_id,
                    'shipping_method_id' => $cart->shipping_method_id,
                    'number'             => $voucher->number,
                    'is_manual'          => false
                ]);
                $this->showSuccessToast('Ο κωδικός αποστολής δημιουργήθηκε με επιτυχία!');
            } else {
                $this->showErrorToast($voucher->statusMessage);
            }
        } catch (Throwable $e) {
            $this->showErrorToast($e->getMessage());
        }
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
        try {
            $shippingMethod->cancelVoucher($voucher->number);
            $voucher->update(['cancelled_at' => now()]);
            $this->showSuccessToast("Ο κωδικός αποστολής $voucher->number ακυρώθηκε.");
        } catch (Throwable $e) {
            $this->showErrorToast($e->getMessage());
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
