<?php

namespace App\Http\Controllers\Account;

use Eshop\Controllers\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class UserOrdersController extends Controller
{
    public function index(): Renderable
    {
        $orders = auth()
            ->user()
            ->carts()
            ->submitted()
            ->with('status', 'paymentMethod', 'shippingMethod')
            ->latest('submitted_at')
            ->paginate();

        return view('account.order.index', compact('orders'));
    }

    public function show(string $lang, Cart $order): RedirectResponse|Renderable
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->isSubmitted()) {
            return redirect()->route('account.orders.index', $lang);
        }

        $order->load(['products' => fn($q) => $q->with('translation', 'image')]);
        $products = $order->products;

        return view('account.order.show', compact('order', 'products'));
    }
}
