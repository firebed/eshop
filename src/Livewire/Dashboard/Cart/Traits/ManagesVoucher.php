<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Eshop\Repository\Contracts\CartContract;

trait ManagesVoucher
{
    public ?string $voucher = "";
    public bool $showVoucherModal = false;
    public ?string $voucherUrl;

    public function mountManagesVoucher(): void
    {
        $this->voucher = $this->cart->voucher;

        $this->updateVoucherUrl();
    }

    public function editVoucher(): void
    {
        $this->voucher = $this->cart->voucher;
        $this->showVoucherModal = true;
        $this->skipRender();
    }

    private function updateVoucherUrl(): void
    {
        if ($this->voucher) {
            $countryShippingMethod = $this->cart->shippingMethod()->first();
            $shippingMethod = $countryShippingMethod->shippingMethod;
            $this->voucherUrl = $shippingMethod
                ? $shippingMethod->getVoucherUrl($this->voucher)
                : NULL;
        } else {
            $this->voucherUrl = null;
        }
    }

    public function saveVoucher(CartContract $contract): void
    {
        $this->voucher = $this->trim($this->voucher);
        if ($contract->setVoucher($this->cart, $this->voucher)) {
            $this->showVoucherModal = false;

            $this->updateVoucherUrl();

            $this->showSuccessToast('Voucher saved!');
        } else {
            $this->addError('voucher', 'An error occurred. The voucher code was not updated.');
        }
    }
}
