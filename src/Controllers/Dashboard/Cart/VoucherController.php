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
    public function create(Request $request, CourierService $courierService): Renderable
    {
        $ids = json_decode($request->query('ids'));
        $carts = Cart::whereKey($ids)
            ->with('paymentMethod', 'shippingMethod', 'shippingAddress.country', 'voucher')
            //->whereDoesntHave('voucher')
            ->latest('submitted_at')
            ->get()
            ->sortBy('shippingMethod.id')
            ->keyBy('id');

        $billingCodes = eshop('acs.billing_codes');

        return $this->view('voucher.create', compact('carts', 'billingCodes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->flash();
        return back();
    }

    public function searchStations(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_method_id' => ['required', 'int', 'exists:shipping_methods,id'],
            'street'             => ['required', 'string'],
            'street_no'          => ['nullable', 'string'],
            'postcode'           => ['required', 'string'],
        ]);

        $address = $request->input();

        $shippingMethod = ShippingMethod::find($request->input('shipping_method_id'));
        $stations = $shippingMethod->validateAddress($address['street'], $address['street_no'], null, $address['postcode']);

        return response()->json($stations);
    }

    public function searchAreas(Request $request, AcsFindAreaByZipcode $findArea): JsonResponse
    {
        $request->validate([
            'postcode' => ['required', 'string'],
        ]);

        $shippingMethod = ShippingMethod::find($request->input('shipping_method_id'));
        $stations = $shippingMethod->stations($request->input('postcode'));

        return response()->json($stations);
    }


    public function destroy(Cart $cart)
    {

    }
}
