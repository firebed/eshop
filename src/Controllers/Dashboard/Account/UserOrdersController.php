<?php

namespace Eshop\Controllers\Dashboard\Account;

use Eshop\Controllers\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\View\View;

class UserOrdersController extends Controller
{
    public function index(): View
    {
        $orders = auth()
            ->user()
            ->carts()
            ->submitted()
            ->with('status', 'paymentMethod', 'shippingMethod')
            ->latest('submitted_at')
            ->paginate();

        return view('eshop::customer.account.order.index', compact('orders'));
    }

    public function show(string $lang, Cart $order): View
    {
        $order->load(['products' => fn($q) => $q->with('translation', 'image')]);
        $products = $order->products;

        return view('eshop::customer.account.order.show', compact('order', 'products'));
    }
}
