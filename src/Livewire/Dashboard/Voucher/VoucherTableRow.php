<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Actions\CreateVoucherRequest;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Courier\CourierService;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Throwable;

class VoucherTableRow extends Component
{
    public string      $number            = '';
    public int         $cart_id;
    public array       $voucher;
    public bool|string $addressValidation = '';

    public function mount(Cart $cart, CreateVoucherRequest $voucherRequest)
    {
        $this->cart_id = $cart->id;
        $this->number = $cart->voucher()->first()->number ?? "";
        $this->voucher = $voucherRequest->handle($cart);

        //$this->validateAddress();
    }

    public function createVoucher(CourierService $courierService): bool
    {
        $this->resetErrorBag();

        $cart = Cart::find($this->cart_id);
        if ($cart->voucher()->exists()) {
            return true;
        }

        try {
            $courier_id = $cart->shippingMethod->courier()->value;
            $voucher = $courierService->createVoucher($courier_id, $this->voucher);

            //Voucher::create([
            //    'cart_id'    => $voucher['reference_1'],
            //    'courier_id' => $courier_id,
            //    'number'     => $voucher['number'],
            //    'is_manual'  => false,
            //    'meta'       => ['uuid' => $voucher['uuid']]
            //]);

            $this->number = $voucher['number'];
            return true;
        } catch (Throwable $e) {
            $this->addError('courier', $e->getMessage());
            return false;
        }
    }

    public function validateAddress()
    {
        $service = new CourierService();

        $area = $service->validateArea(
            $this->voucher['address'],
            $this->voucher['address_number'],
            $this->voucher['postcode'],
            $this->voucher['region'],
        );
        try {
            //$this->addressValidation = $area['region'] . ' ' . $area['confidence'];
            if (empty($area)) {
                $this->addressValidation = false;
            } elseif ($area['type'] === 'ΔΠ') {
                $this->addressValidation = "Δυσπρόσιτη περιοχή.";
            } elseif ($area['type'] === 'ΔΧ') {
                $this->addressValidation = "Δυσπρόσιτη χωρίς χρέωση. Καθυστέρηση 1-2 ημέρες.";
            }
        } catch (Exception $e) {
            dd($e->getMessage(), $area);
        }
    }

    public function render(): Renderable
    {
        $cart = Cart::find($this->cart_id);

        return view('eshop::dashboard.voucher.wire.table-row', [
            'cart' => $cart
        ]);
    }
}