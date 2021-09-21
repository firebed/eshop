<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Requests\CheckoutDetailsRequest;
use Eshop\Actions\Order\RefreshOrder;
use Eshop\Controllers\Controller;
use Eshop\Models\Cart\DocumentType;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class CheckoutDetailsController extends Controller
{
    public function edit(Request $request, Order $order, RefreshOrder $refreshOrder): Renderable|RedirectResponse
    {
        if ($order->isEmpty()) {
            return redirect()->route('checkout.products.index', app()->getLocale());
        }

        DB::transaction(function () use ($refreshOrder, $order) {
            $refreshOrder->handle($order);
            if ($refreshOrder->totalHasChanged()) {
                session()->flash('products-values-changed');
            }
        });

        $has_shipping_methods = false;
        $country_id = $request->old('shippingAddress.country_id', $order->shippingAddress->country_id ?? null);
        $provinces = [];
        if ($country = Country::find($country_id)) {
            $has_shipping_methods = $country->filterShippingOptions($order->products_value)->isNotEmpty();
            $provinces = $country->provinces()->where('shippable', true)->orderBy('name')->pluck('name');
        }

        $calculator = $refreshOrder->shippingFeeCalculator;

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        $userCountry = Country::code(Location::get($request->ip())->countryCode)->first() ?? Country::default();

        return view('checkout.details.edit', [
            'order'                 => $order,
            'products'              => $products,
            'userCountry'           => $userCountry,
            'addresses'             => Auth::check() ? user()->addresses : collect(),
            'countries'             => Country::visible()->orderBy('name')->get(),
            'provinces'             => $provinces,
            'shipping'              => $order->shippingAddress?->related_id !== null ? null : $order->shippingAddress,
            'invoice'               => $order->invoice,
            'selected_shipping_id'  => $order->shippingAddress?->related_id,
            'invoicing'             => $order->document_type === DocumentType::INVOICE,
            'has_shipping_methods'  => $has_shipping_methods,
            'inaccessible_area_fee' => $calculator->getInaccessibleAreaFee(),
            'excess_weight_fee'     => $calculator->getExcessWeightFee(),
            'weight_limit'          => $calculator->getMethod()?->weight_limit ?: 0
        ]);
    }

    public function update(string $lang, CheckoutDetailsRequest $request, Order $order, RefreshOrder $refreshOrder): RedirectResponse
    {
        DB::transaction(function () use ($refreshOrder, $order) {
            $refreshOrder->handle($order);
            if ($refreshOrder->totalHasChanged()) {
                session()->flash('products-values-changed');
            }
        });

        $order->document_type = $request->filled('invoicing') ? DocumentType::INVOICE : DocumentType::RECEIPT;
        $order->details = $request->input('details');
        $order->email = Auth::check() ? auth()->user()->email : $request->input('email');

        if ($request->filled('selected_shipping_id')) {
            $shipping = Address::find($request->input('selected_shipping_id'));
            $clone = $shipping->replicate(['addressable_type', 'addressable_id']);
            $clone->related_id = $shipping->id;
            $clone->cluster = 'shipping';
            $order->shippingAddress()->updateOrCreate([], $clone->getAttributes());
        } else {
            $shipping = array_merge($request->input('shippingAddress'), [
                'cluster'    => 'shipping',
                'related_id' => null
            ]);

            $order->shippingAddress()->updateOrCreate([], $shipping);
        }

        $invoiceFilled = collect($request->input('invoice'))->merge($request->input('invoiceAddress'))->filter()->isNotEmpty();
        if ($invoiceFilled) {
            $invoice = $order->invoice()->updateOrCreate([], $request->input('invoice'));
            $invoice->billingAddress()->updateOrCreate([], $request->input('invoiceAddress'));
        }

        $order->save();

        return redirect()->route('checkout.payment.edit', $lang);
    }

    public function userShipping(Request $request, Order $order, RefreshOrder $refreshOrder): JsonResponse
    {
        $order->shippingAddress->related_id = $request->input('selected_shipping_id');

        $refreshOrder->handle($order);

        $has_shipping_methods = false;
        $country = $order->shippingAddress->country ?? null;
        if ($country) {
            $has_shipping_methods = $country->filterShippingOptions($order->products_value)->isNotEmpty();
        }

        $calculator = $refreshOrder->shippingFeeCalculator;

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        return response()->json(view('checkout.details.partials.checkout-details-summary', [
            'order'                 => $order,
            'products'              => $products,
            'shipping'              => $order->shipping,
            'has_shipping_methods'  => $has_shipping_methods,
            'inaccessible_area_fee' => $calculator->getInaccessibleAreaFee(),
            'excess_weight_fee'     => $calculator->getExcessWeightFee(),
            'weight_limit'          => $calculator->getMethod()?->weight_limit ?: 0
        ])->render());
    }

    public function shippingCountry(Request $request, Order $order, RefreshOrder $refreshOrder): JsonResponse
    {
        $order->shippingAddress->country_id = $request->input('country_id');
        $order->shippingAddress->postcode = $request->input('postcode');

        $refreshOrder->handle($order);

        $has_shipping_methods = false;
        $country = $order->shippingAddress->country ?? null;
        $order->shippingAddress->province = null;
        $provinces = collect();
        if ($country) {
            $has_shipping_methods = $country->filterShippingOptions($order->products_value)->isNotEmpty();
            $provinces = $country->provinces()
                ->where('shippable', true)
                ->orderBy('name')
                ->pluck('name');
        }

        $calculator = $refreshOrder->shippingFeeCalculator;

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        $summary = view('checkout.details.partials.checkout-details-summary', [
            'order'                 => $order,
            'products'              => $products,
            'shipping'              => $order->shippingAddress,
            'has_shipping_methods'  => $has_shipping_methods,
            'inaccessible_area_fee' => $calculator->getInaccessibleAreaFee(),
            'excess_weight_fee'     => $calculator->getExcessWeightFee(),
            'weight_limit'          => $calculator->getMethod()?->weight_limit ?: 0
        ])->render();

        $provinces = view('checkout.details.partials.provinces', [
            'shipping'  => $order->shippingAddress,
            'provinces' => $provinces,
        ])->render();

        return response()->json(compact('summary', 'provinces'));
    }
}
