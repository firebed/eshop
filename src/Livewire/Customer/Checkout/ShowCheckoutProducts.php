<?php


namespace Ecommerce\Livewire\Customer\Checkout;


use Ecommerce\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Ecommerce\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowCheckoutProducts extends Component
{
    use ControlsOrder;

    public array $quantities;

    public function mount(Order $order): void
    {
        if ($order->isNotEmpty() && DB::transaction(fn() => $this->softRefreshOrder($order))) {
            session()->flash('products-values-changed');
        }
    }

    public function updatedQuantities($quantity, $productId): void
    {
        $quantity = is_numeric($quantity) ? $quantity : 0;

        $order = app(Order::class);
        $this->updateProduct($order, $productId, $quantity);
    }

    public function deleteProduct($productId): void
    {
        $order = app(Order::class);
        $this->removeProduct($order, $productId);
    }

    public function render(Order $order): Renderable
    {
        if ($order->isEmpty()) {
            return view('customer.checkout.products.wire.index', [
                'order'    => $order,
            ]);
        }

        $this->quantities = $order->pluckProductQuantities()->all();

        $products = $order->products;
        if ($products->isNotEmpty()) {
            $products->load('image', 'parent', 'category', 'options');
            $products->merge($products->pluck('parent')->filter())->load('translation');
        }

        $shippingMethods = $order->shippingAddress->country->shippingMethodRange($order->shipping_method_id);

        return view('com::customer.checkout.products.wire.index', [
            'order'           => $order,
            'shippingMethods' => $shippingMethods,
            'nextShipping'    => $shippingMethods->where('fee', '<', $order->shipping_fee)->last(),
            'lastShipping'    => $shippingMethods->last()
        ]);
    }
}
