<?php

namespace App\Http\Livewire\Checkout;


use App\Http\Livewire\Checkout\Concerns\ControlsOrder;
use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Repository\Contracts\Order;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowCheckoutProducts extends Component
{
    use ControlsOrder;

    public array $quantities;

    public function mount(Order $order, RefreshOrder $refreshOrder): void
    {
        if ($order->isNotEmpty()) {
            DB::transaction(function () use ($refreshOrder, $order) {
                $refreshOrder->handle($order);
                if ($refreshOrder->totalHasChanged()) {
                    session()->flash('products-values-changed');
                }
            });
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

    public function render(Order $order, ShippingFeeCalculator $sfc): Renderable
    {
        if ($order->isEmpty()) {
            return view('checkout.products.wire.index', [
                'order' => $order,
            ]);
        }

        $this->quantities = $order->pluckProductQuantities()->all();

        $products = $order->products;
        if ($products->isNotEmpty()) {
            $products->load('image', 'parent', 'category', 'options');
            $products->merge($products->pluck('parent')->filter())->load('translation');
        }

        $shippingMethods = collect();
        if ($order->shippingAddress && $order->shippingAddress->country && $order->shippingMethod) {
            $country = $order->shippingAddress->country;
            $shippingMethods = $country->shippingOptions
                ->where('shipping_method_id', $order->shipping_method_id)
                ->where('visible', true)
                ->sortBy('fee');
        }

        $defaultShipping = $shippingMethods->last();

        return view('checkout.products.wire.index', [
            'order'                => $order,
            'shippingMethods'      => $shippingMethods,
            'nextShippingDiscount' => $shippingMethods->where('cart_total', '>', $order->products_value)->last(),
            'defaultShipping'      => $defaultShipping,
            'defaultShippingFee'   => $defaultShipping ? $sfc->handle($defaultShipping, $order->parcel_weight, $order->shippingAddress->postcode ?? null) : null,
        ]);
    }
}
