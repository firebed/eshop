<?php

namespace Eshop\Controllers\Dashboard\Pos;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartInvoice;
use Eshop\Models\Cart\CartStatus;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\CountryPaymentMethod;
use Eshop\Models\Location\CountryShippingMethod;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class PosController extends Controller
{
    use WithNotifications;

    public function create(): Renderable
    {
        return $this->view('pos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $query = http_build_query(array_filter(session('pos-models-query', [])));

        $action = $request->input('action');
        try {
            $items = $request->input('items', []);

            $products = Product::findMany(array_keys($items));

            $total = array_reduce($items, static function ($carry, $item) {
                return $carry + $item['quantity'] * $item['price'] * (1 - $item['discount']);
            }, (float)$request->input('shipping_fee') + (float)$request->input('payment_fee'));

            $weight = array_reduce(array_keys($items), static function ($carry, $key) use ($items, $products) {
                $qty = $items[$key]['quantity'];
                $weight = $products->find($key)->weight ?? 0;
                return $carry + $qty * $weight;
            }, 0);

            $cart = new Cart();
            if ($action === 'saveAsOrder') {
                $submitted = CartStatus::firstWhere('name', CartStatus::SUBMITTED);
                $cart->status()->associate($submitted);
            } else {
                $shipped = CartStatus::firstWhere('name', CartStatus::SHIPPED);
                $cart->status()->associate($shipped);
                $cart->viewed_at = now();
            }

            $hasInvoice = !empty(array_filter($request->input('invoice')));

            $countryPaymentMethod = CountryPaymentMethod::find($request->input('country_payment_method_id'));
            $countShippingMethod = CountryShippingMethod::find($request->input('country_shipping_method_id'));

            $cart->user()->associate(auth()->id());
            $cart->document_type = $hasInvoice ? 'Invoice' : 'Receipt';
            $cart->email = $request->input('email');
            $cart->shipping_method_id = $countShippingMethod?->id;
            $cart->payment_method_id = $countryPaymentMethod?->id;
            $cart->shipping_fee = $request->input('shipping_fee');
            $cart->payment_fee = $request->input('payment_fee');
            $cart->submitted_at = now();
            $cart->channel = 'pos';
            $cart->parcel_weight = $weight;
            $cart->ip = request()?->ip();
            $cart->total = $total;

            $pivot = [];
            foreach ($items as $productId => $item) {
                $product = $products->find($productId);
                if ($product) {
                    $pivot[$productId] = [
                        'product_id'    => $productId,
                        'quantity'      => $item['quantity'],
                        'price'         => $item['price'],
                        'compare_price' => $product->compare_price,
                        'discount'      => $item['discount'],
                        'vat'           => $product->vat,
                    ];
                }
            }

            DB::beginTransaction();

            $cart->save();
            $cart->products()->syncWithoutDetaching($pivot);
            $cart->operators()->attach(auth()->id());

            $products = $cart->products;
            foreach ($products as $product) {
                $product->timestamps = false;
                $product->decrement('stock', $product->pivot->quantity);
            }

            $shippingAddress = new Address($request->input('shipping'));
            if (empty($shippingAddress->first_name) && empty($shippingAddress->last_name)) {
                $shippingAddress->first_name = config('app.name');
            }
            $shippingAddress->cluster = 'shipping';
            $cart->shippingAddress()->save($shippingAddress);

            if ($hasInvoice) {
                $invoice = new CartInvoice($request->input('invoice'));
                $cart->invoice()->save($invoice);

                $invoiceAddress = new Address($request->input('invoiceAddress'));
                $invoice->billingAddress()->save($invoiceAddress);
            }

            DB::commit();

            $this->showSuccessNotification('Η παραγγελία καταχωρήθηκε');

            return redirect()->route('pos.edit', [$cart->id, $query]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->showErrorNotification('Σφάλμα!', $e->getMessage());
            return back()->with($query);
        }
    }

    public function edit(Cart $cart): Renderable
    {
        $items = $cart
            ->items()
            ->select('product_id', 'quantity', 'price', 'discount')
            ->get()
            ->map(fn($item) => $item->toArray())
            ->keyBy('product_id')
            ->all();

        return $this->view('pos.edit', compact('cart', 'items'));
    }

    public function update(Request $request, Cart $cart): RedirectResponse
    {
        try {
            $items = $request->input('items', []);

            $products = Product::findMany(array_keys($items));

            $total = array_reduce($items, static function ($carry, $item) {
                return $carry + $item['quantity'] * $item['price'] * (1 - $item['discount']);
            }, (float)$request->input('shipping_fee') + (float)$request->input('payment_fee'));

            $weight = array_reduce(array_keys($items), static function ($carry, $key) use ($items, $products) {
                $qty = $items[$key]['quantity'];
                $weight = $products->find($key)->weight ?? 0;
                return $carry + $qty * $weight;
            }, 0);

            $hasInvoice = !empty(array_filter($request->input('invoice')));

            $countryPaymentMethod = CountryPaymentMethod::find($request->input('country_payment_method_id'));
            $countShippingMethod = CountryShippingMethod::find($request->input('country_shipping_method_id'));

            $cart->document_type = $hasInvoice ? 'Invoice' : 'Receipt';
            $cart->email = $request->input('email');
            $cart->shipping_method_id = $countShippingMethod?->id;
            $cart->payment_method_id = $countryPaymentMethod?->id;
            $cart->shipping_fee = $request->input('shipping_fee');
            $cart->payment_fee = $request->input('payment_fee');
            $cart->parcel_weight = $weight;
            $cart->total = $total;

            $previousProducts = $cart->products;
            $diff = $previousProducts->reject(fn($product) => isset($items[$product->id]));
            DB::beginTransaction();
            $cart->shippingAddress()->update($request->input('shipping'));

            if (!$hasInvoice) {
                $cart->invoice?->delete();
            } else {
                $invoice = $cart->invoice()->updateOrCreate([], $request->input('invoice'));
                $invoice->billingAddress()->updateOrCreate([], $request->input('invoiceAddress'));
            }

            if ($diff->isNotEmpty()) {
                // Products that were removed
                foreach ($diff as $d) {
                    $d->increment('stock', $d->pivot->quantity);
                }

                $cart->products()->detach($diff->pluck('id'));
            }

            foreach ($items as $productId => $item) {
                $product = $products->find($productId);
                if (!$product) {
                    continue;
                }
                if ($previousProducts->contains($productId)) {
                    // The product was already in cart
                    $prev = $previousProducts->find($productId)->pivot;
                    Product::query()->whereKey($productId)->decrement('stock', $item['quantity'] - $prev->quantity);
                } else {
                    Product::query()->whereKey($productId)->decrement('stock', $item['quantity']);
                }

                $cart->products()->syncWithoutDetaching([
                    $productId => [
                        'product_id'    => $productId,
                        'quantity'      => $item['quantity'],
                        'price'         => $item['price'],
                        'compare_price' => $product->compare_price,
                        'discount'      => $item['discount'],
                        'vat'           => $product->vat,
                    ]
                ]);
            }
            $cart->save();
            DB::commit();

            $this->showSuccessNotification('Οι αλλαγές αποθηκεύτηκαν!');
        } catch (Throwable $e) {
            DB::rollBack();
            $this->showErrorNotification('Σφάλμα!', $e->getMessage());
        }

        $query = http_build_query(array_filter(session('pos-models-query', [])));
        return back()->with($query);
    }
}
