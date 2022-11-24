<?php

namespace Eshop\Livewire\Dashboard\Voucher;

use Eshop\Actions\CreateVoucherRequest;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Services\Courier\Courier;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Throwable;

class VoucherTableRow extends Component
{
    use SendsNotifications;

    public Cart   $cart;
    public string $number;

    public function mount(Cart $cart)
    {
        $this->cart = $cart;
        $this->number = $cart->voucher()->first()->number ?? "";
    }

    public function createVoucher(CreateVoucherRequest $voucherRequest, Courier $courier)
    {
        $cart = $this->cart;
        if ($cart->voucher()->exists()) {
            return;
        }

        $sm = $cart->shippingMethod()->first();

        $query = $voucherRequest->handle($cart, $sm->courier());

        try {
            $voucher = $courier->createVoucher($query);

            Voucher::create([
                'cart_id'   => $cart->id,
                'courier'   => $query['courier'],
                'number'    => $voucher['number'],
                'is_manual' => false,
                'meta'      => ['uuid' => $voucher['uuid']]
            ]);

            $this->number = $voucher['number'];
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function render(): Renderable
    {
        $voucher = $this->cart->voucher()->first();

        return view('eshop::dashboard.voucher.wire.table-row', [
            'voucher' => $voucher
        ]);
    }
}