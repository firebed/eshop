<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Livewire\Dashboard\Cart\Traits\ManagesVoucher;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Models\Location\ShippingMethod;
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

    public bool $show                = false;
    public bool $showBuyVoucherModal = false;
    public int  $cart_id;

    protected array $rules = [
        'editingVoucher.shipping_method_id' => 'required|integer|exists:shipping_methods,id',
        'editingVoucher.number'             => 'required|string|max:255'
    ];

    private Collection $checkpoints;

    public function trace(Voucher $voucher, Courier $courier)
    {
        try {
            $method = Couriers::tryFrom($voucher->courier);
            $this->checkpoints = $courier->trace($method, $voucher->number);
            $this->show = true;
        } catch (Throwable $e) {
            $this->showErrorToast("Σφάλμα", $e->getMessage());
        }
    }

    public function render(): Renderable
    {
        $cart = Cart::find($this->cart_id);
        $courier = Couriers::tryFrom($this->voucher['courier']);

        return view('eshop::dashboard.cart.wire.track-and-trace', [
            'checkpoints'     => $this->checkpoints ?? collect(),
            'shippingMethods' => ShippingMethod::where('is_courier', true)->where('name', '!=', 'ΚΤΕΛ')->pluck('name', 'id'),
            //'vouchers'        => $this->vouchers,
            'icons'           => collect(Couriers::cases())->mapWithKeys(fn($c) => [$c->value => asset('images/' . $c->icon())]),
            'contentTypes'    => ContentType::cases(),
            'services'        => $courier?->services($cart->shippingAddress->country->code) ?? [],
            'currentVoucher'  => $cart->voucher()->first()
        ]);
    }
}