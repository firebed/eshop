<?php


namespace Eshop\Livewire\Dashboard\Cart\Traits;


use Eshop\Models\Cart\Voucher;
use Eshop\Repository\Contracts\CartContract;
use Illuminate\Support\Collection;

trait ManagesVoucher
{
    private Collection $vouchers;
    
    private ?string $voucher = null;
    public bool $showVoucherModal = false;
    public ?string $voucherUrl;

    public function mountManagesVoucher(): void
    {
        $this->vouchers = collect();

        $this->updateVoucherUrl();
    }

    public function editVoucher(): void
    {
        $this->showVoucherModal = true;
        $this->skipRender();
    }

    private function updateVoucherUrl(): void
    {
        //if ($this->voucher) {
        //    $countryShippingMethod = $this->cart->shippingMethod()->first();
        //    $shippingMethod = $countryShippingMethod?->shippingMethod;
        //    $this->voucherUrl = $shippingMethod?->getVoucherUrl($this->voucher);
        //} else {
        //    $this->voucherUrl = null;
        //}
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

    public function renderingManagesVoucher(): void
    {
        $this->vouchers = collect();
        
        $this->vouchers = Voucher::where('cart_id', $this->cart_id)->latest()->pluck('number');
        $this->voucher = $this->vouchers->first();
    }
}
