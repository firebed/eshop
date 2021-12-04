<?php

namespace Eshop\Controllers\Customer\Checkout;

use Eshop\Actions\Order\RefreshOrder;
use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Cart\DocumentType;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Repository\Contracts\Order;
use Eshop\Requests\Customer\CheckoutDetailsRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class CheckoutDetailsController extends Controller
{
    public function edit(string $lang, Request $request, Order $order, RefreshOrder $refreshOrder): Renderable|RedirectResponse
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return redirect()->route('checkout.products.index', app()->getLocale());
        }

        if ($order->shippingAddress === null && auth()->user()?->addresses->isNotEmpty()) {
            $replicator = auth()->user()->addresses->first();
            $address = $replicator->replicate();
            $address->related_id = $replicator->id;
            $address->cluster = 'shipping';
            $order->shippingAddress()->save($address);
            $order->setRelation('shippingAddress', $address);
        }

        if ($this->refreshOrder($refreshOrder, $order)) {
            session()->flash('products-values-changed');
            return redirect()->route('checkout.products.index', $lang);
        }

        if (!$this->checkProductStocks($order)) {
            session()->flash('insufficient-quantity');
            return redirect()->route('checkout.products.index', $lang);
        }

        // If the order's shipping address is related to a deleted address
        // then also delete the order's current address so that
        // "New address" option is automatically selected
        if (isset($order->shippingAddress->related_id)) {
            Address::whereKey($order->shippingAddress->related_id)->existsOr(function () use ($order) {
                $order->shippingAddress->delete();
                $order->unsetRelation('shippingAddress');
            });
        }

        $country_id = $request->old('shippingAddress.country_id', $order->shippingAddress->country_id ?? null);

        $location = Location::get($request->ip());
        $userCountry = $location ? Country::code($location->countryCode)->first() : Country::default();

        $country = $userCountry;
        if ($country_id && $c = Country::find($country_id)) {
            $country = $c;
        }

        $has_shipping_methods = $country?->filterShippingOptions($order->products_value)->isNotEmpty();
        $provinces = $country?->provinces()->where('shippable', true)->orderBy('name')->pluck('name');

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        return $this->view('checkout.details.edit', [
            'order'                => $order,
            'products'             => $products,
            'userCountry'          => $userCountry,
            'addresses'            => Auth::check() ? auth()->user()->addresses : collect(),
            'countries'            => Country::visible()->orderBy('name')->get(),
            'provinces'            => $provinces ?? collect(),
            'shipping'             => $order->shippingAddress?->related_id !== null ? null : $order->shippingAddress,
            'invoice'              => $order->invoice,
            'selected_shipping_id' => $order->shippingAddress?->related_id,
            'invoicing'            => $order->document_type === DocumentType::INVOICE,
            'has_shipping_methods' => $has_shipping_methods,
        ]);
    }

    public function update(string $lang, CheckoutDetailsRequest $request, Order $order, RefreshOrder $refreshOrder): RedirectResponse
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return redirect()->route('checkout.products.index', app()->getLocale());
        }

        if ($this->refreshOrder($refreshOrder, $order)) {
            session()->flash('products-values-changed');
            return redirect()->route('checkout.products.index', $lang);
        }

        if (!$this->checkProductStocks($order)) {
            session()->flash('insufficient-quantity');
            return redirect()->route('checkout.products.index', $lang);
        }

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
        if ($order->isEmpty() || $order->isSubmitted()) {
            return response()->json();
        }

        $country = null;
        $address = null;
        if ($request->filled('selected_shipping_id')) {
            $related = Address::find($request->input('selected_shipping_id'));
            if ($related) {
                $address = $related->replicate();
                $address->cluster = 'shipping';
                $address->related_id = $related->id;
                $order->shippingAddress()->updateOrCreate([], $address->getAttributes());
            }
        }

        if ($address === null) {
            $location = Location::get($request->ip());
            $country = $location ? Country::code($location->countryCode)->first() : Country::default();

            $address = new Address();
            $address->country()->associate($country);
            $address->cluster = 'shipping';
            $order->shippingAddress()->delete();
            $order->shippingAddress()->save($address);
        }

        DB::transaction(fn() => $refreshOrder->handle($order));

        $has_shipping_methods = false;
        if ($country !== null) {
            $has_shipping_methods = $country->filterShippingOptions($order->products_value)->isNotEmpty();
        }

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        return response()->json($this->view('checkout.details.partials.checkout-details-summary', [
            'order'                => $order,
            'products'             => $products,
            'shipping'             => $order->shippingAddress,
            'has_shipping_methods' => $has_shipping_methods,
        ])->render());
    }

    public function shippingCountry(Request $request, Order $order, RefreshOrder $refreshOrder): JsonResponse
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return response()->json();
        }

        $address = $order->shippingAddress()->firstOrCreate([], ['cluster' => 'shipping']);
        $order->setRelation('shippingAddress', $address);

        $address->country_id = $request->input('country_id');
        $address->related_id = null;
        $address->postcode = $request->input('postcode');
        $address->save();

        DB::transaction(fn() => $refreshOrder->handle($order));

        $has_shipping_methods = false;
        $country = $address->country ?? null;
        $provinces = collect();
        if ($country) {
            $has_shipping_methods = $country->filterShippingOptions($order->products_value)->isNotEmpty();
            $provinces = $country->provinces()
                ->where('shippable', true)
                ->orderBy('name')
                ->pluck('name');
        }

        $products = $order->products;
        $products->load('parent', 'options');
        $products->merge($order->products->pluck('parent')->filter())->load('translation');

        $summary = $this->view('checkout.details.partials.checkout-details-summary', [
            'order'                => $order,
            'products'             => $products,
            'shipping'             => $address,
            'has_shipping_methods' => $has_shipping_methods,
        ])->render();

        $provinces = $this->view('checkout.details.partials.provinces', [
            'shipping'  => $order->shippingAddress,
            'provinces' => $provinces,
        ])->render();

        return response()->json(compact('summary', 'provinces'));
    }

    private function refreshOrder(RefreshOrder $refreshOrder, Order $order): bool
    {
        DB::transaction(fn() => $refreshOrder->handle($order));
        return $refreshOrder->totalHasChanged();
    }

    private function checkProductStocks(Order $order): bool
    {
        $products = $order->products;
        foreach ($products as $product) {
            if (!$product->canBeBought($product->pivot->quantity)) {
                return false;
            }
        }

        return true;
    }
}
