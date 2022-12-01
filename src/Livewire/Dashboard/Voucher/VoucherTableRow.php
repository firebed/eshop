<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Actions\CreateVoucherRequest;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Courier\CourierService;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Throwable;

class VoucherTableRow extends Component
{
    public int    $cart_id;
    public string $number;

    public function mount(Cart $cart)
    {
        $this->cart_id = $cart->id;
        $this->number = $cart->voucher()->first()->number ?? "";
    }

    public function createVoucher(CreateVoucherRequest $voucherRequest, CourierService $courierService)
    {
        $cart = Cart::find($this->cart_id);
        if ($cart->voucher()->exists()) {
            return response($cart->voucher->number);
        }

        $query = $voucherRequest->handle($cart);

        try {
            $courier_id = $cart->shippingMethod->courier()->value;
            $voucher = $courierService->createVoucher($courier_id, $query);

            //Voucher::create([
            //    'cart_id'    => $voucher['reference_1'],
            //    'courier_id' => $courier_id,
            //    'number'     => $voucher['number'],
            //    'is_manual'  => false,
            //    'meta'       => ['uuid' => $voucher['uuid']]
            //]);

            $this->number = $voucher['number'];
            return response($this->number);
        } catch (Throwable $e) {
            $this->addError('courier', $e->getMessage());
            return response($e->getMessage(), 422);
            //$this->dispatchBrowserEvent('voucher-failed', ['cart_id' => $this->cart_id]);
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