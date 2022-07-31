<?php

namespace Eshop\Livewire\Customer\Checkout;


use Eshop\Livewire\Customer\Checkout\Concerns\ControlsOrder;
use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowCheckoutProducts extends Component
{
    use ControlsOrder;
    use SendsNotifications;

    public array $quantities;

    public function mount(Order $order, RefreshOrder $refreshOrder): void
    {
        if ($order->isNotEmpty()) {
            DB::transaction(function () use ($refreshOrder, $order) {
                $refreshOrder->handle($order);
                if ($refreshOrder->productsTotalHasChanged()) {
                    session()->flash('products-values-changed');
                }
            });
        }
    }

    public function updatedQuantities($quantity, $productId): void
    {
        $quantity = is_numeric($quantity) ? $quantity : 0;

        $product = Product::find($productId);

        $order = app(Order::class);
        $this->updateProduct($order, $productId, $quantity);

//        if(!$product->canBeBought($quantity)) {
//            $this->showWarningDialog($product->trademark, __("eshop::order.max_available_stock", ['quantity' => $quantity, 'available' => $product->available_stock]));
//        }
    }

    public function deleteProduct($productId): void
    {
        $order = app(Order::class);
        $this->removeProduct($order, $productId);
    }

    public function render(Order $order, ShippingFeeCalculator $sfc): Renderable
    {
        if ($order->isEmpty()) {
            return view('eshop::customer.checkout.products.wire.index', [
                'order' => $order,
            ]);
        }

        $this->quantities = $order->pluckProductQuantities()->all();

        $products = $order->products;
        if ($products->isNotEmpty()) {
            $products->load('image', 'parent', 'category', 'variantOptions.translations');
            $products->merge($products->pluck('parent')->filter())->load('translations');
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

        $this->emit('setCartItemsCount', $order->items()->count());

        return view('eshop::customer.checkout.products.wire.index', [
            'order'                => $order,
            'shippingMethods'      => $shippingMethods,
            'nextShippingDiscount' => $shippingMethods->where('cart_total', '>', $order->products_value)->last(),
            'defaultShipping'      => $defaultShipping,
            'defaultShippingFee'   => $defaultShipping ? $sfc->handle($defaultShipping, $order->parcel_weight, $order->shippingAddress->postcode ?? null) : null,
        ]);
    }
}
