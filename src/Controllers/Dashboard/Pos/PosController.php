<?php

namespace Eshop\Controllers\Dashboard\Pos;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartStatus;
use Eshop\Models\Product\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class PosController extends Controller
{
    use WithNotifications;

    public function create(): View
    {
        return view('eshop::dashboard.pos.create');
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

            $cart->shipping_method_id = $request->input('shipping_method_id');
            $cart->payment_method_id = $request->input('payment_method_id');
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
            $cart->products()->sync($pivot);
            $this->decrementProductStocks($cart);

            foreach ($items as $productId => $item) {
                Product::query()
                    ->whereKey($productId)
                    ->decrement('stock', $item['quantity']);
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

    public function edit(Cart $cart): View
    {
        $items = $cart
            ->items()
            ->select('product_id', 'quantity', 'price', 'discount')
            ->get()
            ->map(fn($item) => $item->toArray())
            ->keyBy('product_id')
            ->all();

        return view('eshop::dashboard.pos.edit', compact('cart', 'items'));
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

            $cart->shipping_method_id = $request->input('shipping_method_id');
            $cart->payment_method_id = $request->input('payment_method_id');
            $cart->shipping_fee = $request->input('shipping_fee');
            $cart->payment_fee = $request->input('payment_fee');
            $cart->parcel_weight = $weight;
            $cart->total = $total;

            $previousProducts = $cart->products;
            $diff = $previousProducts->reject(fn($product) => isset($items[$product->id]));
            DB::beginTransaction();
            $cart->save();

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
            DB::commit();
            $this->showSuccessNotification('Οι αλλαγές αποθηκεύτηκαν!');
        } catch (Throwable $e) {
            DB::rollBack();
            $this->showErrorNotification('Σφάλμα!', $e->getMessage());
        }

        $query = http_build_query(array_filter(session('pos-models-query', [])));
        return back()->with($query);
    }

    private function decrementProductStocks(Cart $cart): void
    {
        $products = $cart->products;
        foreach ($products as $cartItem) {
            $cartItem->timestamps = false;
            $cartItem->decrement('stock', $cartItem->pivot->quantity);
        }
    }
}
