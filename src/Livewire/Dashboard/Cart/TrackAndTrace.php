<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Dashboard\Cart\Traits\ManagesVoucher;
use Eshop\Models\Cart\Voucher;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Services\SpeedEx\Exceptions\SpeedExException;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;
use Throwable;

class TrackAndTrace extends Component
{
    use ManagesVoucher;
    use SendsNotifications;

    public bool $show = false;
    public int  $cart_id;

    protected array $rules = [
        'editingVoucher.shipping_method_id' => 'required|integer|exists:shipping_methods,id',
        'editingVoucher.number'             => 'required|string|max:255'
    ];

    private Collection $checkpoints;

    public function trace(Voucher $voucher)
    {
        $shippingMethod = $voucher->shippingMethod;
        try {
            $this->checkpoints = $shippingMethod->trace($voucher->number);
            $this->show = true;
        } catch (Throwable $e) {
            $this->showErrorToast($e->getMessage());
        }
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.cart.wire.track-and-trace', [
            'checkpoints'     => $this->checkpoints ?? collect(),
            'shippingMethods' => ShippingMethod::where('is_courier', true)->where('name', '!=', 'ΚΤΕΛ')->pluck('name', 'id'),
            'vouchers'        => $this->vouchers
        ]);
    }
}