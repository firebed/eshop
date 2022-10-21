<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Carbon\Carbon;
use Eshop\Livewire\Dashboard\Cart\Traits\ManagesVoucher;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Acs\Http\AcsTrackingDetails;
use Eshop\Services\CourierCenter\Http\CourierCenterTracking;
use Eshop\Services\SpeedEx\Http\SpeedExGetTraceByVoucher;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

class TrackAndTrace extends Component
{
    use ManagesVoucher;

    public int $cart_id;

    private Collection $checkpoints;

    public function trace()
    {
        $cart = Cart::find($this->cart_id);

        $shippingMethod = $cart->shippingMethod;
        if ($shippingMethod->name === 'SpeedEx') {
            $this->checkpoints = (new SpeedExGetTraceByVoucher())->handle($cart->voucher)
                ->map(function ($checkpoint) {
                    $city = str($checkpoint['Branch'])->after('-');
                    $date = Carbon::parse($checkpoint['CheckpointDate']);
                    return [
                        'title'       => str($checkpoint['StatusDesc']),
                        'description' => $city . ', ' . $date->format('d/m/Y στις H:i')
                    ];
                });
        } elseif ($shippingMethod->name === 'ACS Courier') {
            $this->checkpoints = (new AcsTrackingDetails())->handle($cart->voucher)
                ->sortByDesc('checkpoint_date_time')
                ->map(function ($checkpoint) {
                    $city = $checkpoint['checkpoint_location'];
                    $date = Carbon::parse($checkpoint['checkpoint_date_time']);
                    return [
                        'title'       => str($checkpoint['checkpoint_action']),
                        'description' => $city . ', ' . $date->format('d/m/Y στις H:i')
                    ];
                });
        } elseif ($shippingMethod->name === 'Courier Center') {
            $this->checkpoints = (new CourierCenterTracking())->handle($cart->voucher)
                ->sortByDesc('ExecutedOn')
                ->map(function ($checkpoint) {
                    $city = $checkpoint['StationName'];
                    $date = Carbon::parse($checkpoint['ExecutedOn']);
                    return [
                        'title'       => str($checkpoint['Note']),
                        'description' => $city . ', ' . $date->format('d/m/Y στις H:i')
                    ];
                });
        }
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.cart.wire.track-and-trace', [
            'checkpoints' => $this->checkpoints ?? collect(),
            'voucher'     => $this->voucher,
            'vouchers'    => $this->vouchers
        ]);
    }
}