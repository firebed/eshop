<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Dashboard\Cart\Traits\ManagesVoucher;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Services\Courier\ContentType;
use Eshop\Services\Courier\Courier;
use Eshop\Services\Courier\Couriers;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;
use Throwable;

class TrackAndTrace extends Component
{
    use ManagesVoucher;
    use SendsNotifications;

    public bool $show                   = false;
    public bool $showBuyVoucherModal    = false;
    public bool $showDeleteVoucherModal = false;
    public bool $propagate_delete       = true;
    public bool $saveOnMyShipping       = true;
    public int  $cart_id;

    protected array $rules = [
        'editingVoucher.courier' => ['required', 'integer'],
        'editingVoucher.number'  => 'required|string|max:255'
    ];

    private Collection $checkpoints;

    public function trace(Voucher $voucher, Courier $courier)
    {
        try {
            $this->checkpoints = $courier->trace($voucher);
            $this->show = true;
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function render(): Renderable
    {
        $cart = Cart::find($this->cart_id);

        return view('eshop::dashboard.cart.wire.track-and-trace', [
            'checkpoints'    => $this->checkpoints ?? collect(),
            'icons'          => collect(Couriers::cases())->mapWithKeys(fn($c) => [$c->value => asset('images/' . $c->icon())]),
            'contentTypes'   => ContentType::cases(),
            'services'       => $this->services,
            'cart'           => $cart,
            'currentVoucher' => $cart->voucher()->first()
        ]);
    }
}