<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Voucher;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function create(): Renderable
    {
        $vouchers = Voucher::notCancelled()
            ->whereDoesntHave('pickups')
            ->with(['cart.shippingAddress', 'cart.paymentMethod', 'shippingMethod'])
            ->get()
            ->sortBy('shippingMethod.id');

        $totals = $vouchers->groupBy('shippingMethod.id')
            ->map(fn($c) => [
                'icon'  => $c->first()->shippingMethod->iconSrc(),
                'count' => $c->count()
            ]);

        return $this->view('pickup.create', compact('vouchers', 'totals'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->flash();
        return back();
    }
}
