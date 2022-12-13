<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Courier\CourierService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

class VoucherController extends Controller
{
    public function index(Request $request, CourierService $courierService): Renderable
    {
        $request->validate([
            'date' => ['nullable', 'date']
        ]);

        $date = $request->date('date');
        $pending = $request->boolean('pending');
        
        if ($date === null && !$pending) {
            $date = today();
        }

        $vouchers = $courierService->vouchers($pending, $date);

        if ($pending) {
            Cache::put('pending-vouchers-count', $vouchers->count());
        }

        $cartIds = $vouchers->pluck('reference_1');
        $carts = Cart::whereKey($cartIds)->with('shippingAddress')->get();

        return $this->view($pending ? "voucher.pending" : "voucher.index", compact('carts', 'vouchers', 'date'));
    }

    public function create(Request $request, CourierService $courierService): Renderable
    {
        $ids = json_decode($request->query('ids'));
        $carts = Cart::query()
            ->whereKey($ids)
            //->whereIn('status_id', [1, 2, 3]) // Approved + Completed
            //->whereNotNull('viewed_at')
            //->whereBetween('submitted_at', [now()->subDays(5), now()])
            ->whereHas('shippingMethod')
            ->whereHas('paymentMethod')
            //->whereDoesntHave('voucher')
            ->with('paymentMethod', 'shippingMethod', 'shippingAddress.country', 'voucher')
            ->latest('submitted_at')
            ->get()
            ->keyBy('id');

        $billingCodes = eshop('acs.billing_codes');

        return $this->view('voucher.create', compact('carts', 'billingCodes'));
    }

    public function submit(CourierService $courierService): RedirectResponse
    {
        try {
            $response = $courierService->submitPendingVouchers();

            return back()
                ->with(['submitted' => count($response['submitted'])])
                ->withErrors($response['errors']);
        } catch (Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
