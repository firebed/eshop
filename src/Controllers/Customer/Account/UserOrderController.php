<?php

namespace Eshop\Controllers\Customer\Account;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class UserOrderController extends Controller
{
    public function index(): Renderable
    {
        $orders = auth()
            ->user()
            ?->carts()
            ->submitted()
            ->with('status', 'paymentMethod', 'shippingMethod', 'shippingAddress')
            ->latest('submitted_at')
            ->paginate();

        return $this->view('account.order.index', compact('orders'));
    }

    public function show(string $lang, Cart $order): RedirectResponse|Renderable
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->isSubmitted()) {
            return redirect()->route('account.orders.index', $lang);
        }

        $order->load(['products' => fn($q) => $q->with('translation', 'image', 'parent.translation', 'variantOptions.translation')]);
        $products = $order->products;

        return $this->view('account.order.show', compact('order', 'products'));
    }
}
