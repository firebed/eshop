<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Actions\CreateVoucherRequest;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Services\Courier\CourierService;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Throwable;

class VoucherTableRow extends Component
{
    public string $number = '';
    public int    $cart_id;
    public array  $voucher;

    public function mount(Cart $cart, CreateVoucherRequest $voucherRequest)
    {
        $this->cart_id = $cart->id;
        $this->number = $cart->voucher()->first()->number ?? "";
        $this->voucher = $voucherRequest->handle($cart);
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

            Voucher::create([
                'cart_id'    => $voucher['reference_1'],
                'courier_id' => $courier_id,
                'number'     => $voucher['number'],
                'is_manual'  => false,
                'meta'       => ['uuid' => $voucher['uuid']]
            ]);

            $this->number = $voucher['number'];
            return true;
        } catch (Throwable $e) {
            $this->addError('courier', $e->getMessage());
            return false;
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