<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Services\Acs\Http\AcsFindAreaByZipcode;
use Eshop\Services\Courier\CourierService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoucherController extends Controller
{
    public function index()
    {
        return $this->view("voucher.index");
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
}
