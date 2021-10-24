<?php

namespace App\Http\Livewire\Checkout\Concerns;


use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;

trait ControlsOrder
{
    protected function updateTotal(Order $order): void
    {
        $refresh = new RefreshOrder(new ShippingFeeCalculator());
        $refresh->handle($order);
    }

    protected function addProduct(Order $order, Product $product, $quantity): bool
    {
        if (!is_numeric($quantity) || $quantity < 0 || $quantity > 9999) {
            return false;
        }

        $order->addProduct($product, $quantity);
        $this->updateTotal($order);
        return true;
    }

    protected function updateProduct(Order $order, Product|int $product, int $quantity): void
    {
        if (!is_numeric($quantity) || $quantity < 0 || $quantity > 9999) {
            return;
        }

        $order->updateQuantity($product, $quantity);
        $this->updateTotal($order);
    }

    protected function removeProduct(Order $order, Product|int $product): void
    {
        $order->removeProduct($product);
        $this->updateTotal($order);
    }
}
