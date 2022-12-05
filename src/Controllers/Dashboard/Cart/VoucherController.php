<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\Voucher;
use Eshop\Services\Courier\CourierService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

class VoucherController extends Controller
{
    public function index(Request $request): Renderable
    {
        $request->validate([
            'date' => ['nullable', 'date']
        ]);

        $date = $request->date('date') ?? today();
        $pending = $request->boolean('pending');

        $vouchers = Voucher::query()
            ->whereNotNull('courier_id')
            ->with('cart.shippingAddress')
            ->when($pending, function (Builder $q) {
                $q->whereNull('submitted_at');
                $collection = $q->get();

                Cache::put('pending-vouchers-count', $collection->count());

                return $collection;
            })
            ->when(!$pending, function (Builder $q) use ($date) {
                $q->whereBetween('submitted_at', [$date->startOfDay(), $date->copy()->endOfDay()]);
                $q->whereNotNull('submitted_at');
                return $q->get();
            });

        return $this->view($pending ? "voucher.pending" : "voucher.index", compact('vouchers', 'date'));
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
            ->whereDoesntHave('voucher')
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

            Voucher::query()
                ->whereIn('myshipping_id', $response)
                ->update(['submitted_at' => now()]);

            $pendingVouchers = Voucher::whereNull('submitted_at')->count();
            Cache::put('pending-vouchers-count', $pendingVouchers);

            return back()->with('submitted', count($response));
        } catch (Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
