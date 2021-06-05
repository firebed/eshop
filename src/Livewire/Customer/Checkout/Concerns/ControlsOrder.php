<?php


namespace Eshop\Livewire\Customer\Checkout\Concerns;


use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;

trait ControlsOrder
{
    protected function refreshOrder(Order $order): void
    {
        $order->refreshProducts();
        $this->updateTotal($order);
    }

    protected function softRefreshOrder(Order $order): void
    {
        $order->refreshProducts();
        $this->updateTotal($order, $order->shipping_method_id, $order->payment_method_id);
    }

    protected function addProduct(Order $order, Product|int $product, int $quantity): void
    {
        $order->addProduct($product, $quantity);
        $this->updateTotal($order);
    }

    protected function updateProduct(Order $order, Product|int $product, int $quantity): void
    {
        $order->updateQuantity($product, $quantity);
        $this->updateTotal($order);
    }

    protected function removeProduct(Order $order, Product|int $product): void
    {
        $order->removeProduct($product);
        $this->updateTotal($order);
    }

    protected function updateTotal(Order $order, $preferredShippingMethodId = NULL, $preferredPaymentMethodId = NULL): void
    {
        $order->updateTotalWeight();
        $order->updateFees($preferredShippingMethodId, $preferredPaymentMethodId);
        $order->updateTotal();
        $order->save();
    }
}
