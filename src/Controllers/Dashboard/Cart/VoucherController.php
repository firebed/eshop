<?php

namespace Eshop\Controllers\Dashboard\Cart;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Acs\Http\AcsAddressValidation;
use Eshop\Services\Acs\Http\AcsFindAreaByZipcode;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoucherController extends Controller
{
    public function create(Request $request): Renderable
    {
        $ids = json_decode($request->query('ids'));

        $carts = Cart::whereKey($ids)->with('paymentMethod', 'shippingMethod', 'shippingAddress.country')->get()->keyBy('id');
        $billingCodes = eshop('acs.billing_codes');

        return $this->view('voucher.create', compact('carts', 'billingCodes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->flash();
        return back();
    }

    public function searchStations(Request $request, AcsAddressValidation $validation): JsonResponse
    {
        $request->validate([
            'street'    => ['required', 'string'],
            'street_no' => ['nullable', 'string'],
            'postcode'  => ['required', 'string'],
        ]);

        $address = $request->input();
        $stations = $validation->handle($address['street'], $address['street_no'], null, $address['postcode']);

        if ($stations->count() === 1) {
            $station = $stations->first();

            $type = null;
            if ($station['Resolved_As_Inaccesible_Area_With_Cost']) {
                $type = 'ΔΠ';
            } elseif ($station['Resolved_As_Inaccesible_Area_WithOut_Cost']) {
                $type = 'ΔΧ';
            }

            return response()->json([
                'id'   => $station['Resolved_Station_ID'],
                'name' => $station['Resolved_Station_Descr'],
                'type' => $type,
            ]);
        }

        return response()->json([], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function searchAreas(Request $request, AcsFindAreaByZipcode $findArea): JsonResponse
    {
        $request->validate([
            'postcode' => ['required', 'string'],
        ]);

        $areas = $findArea->handle($request->input('postcode'));

        return response()->json($areas);
    }


    public function destroy(Cart $cart)
    {

    }
}
