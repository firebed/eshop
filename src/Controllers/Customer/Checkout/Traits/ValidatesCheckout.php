<?php

namespace Eshop\Controllers\Customer\Checkout\Traits;

use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Repository\Contracts\Order;
use Illuminate\Support\Facades\DB;

trait ValidatesCheckout
{
    protected function validateCheckout(Order $order): bool
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return false;
        }

        if ($this->totalHasChanged($order)) {
            session()->flash('products-values-changed');
            return false;
        }

        if (!$this->checkProductStocks($order)) {
            session()->flash('insufficient-quantity');
            return false;
        }

        return true;
    }

    protected function validateShippingAddress(Order $order): bool
    {
        return $order->shippingAddress !== null && $order->shippingAddress->isFilled();
    }

    protected function totalHasChanged(Order $order): bool
    {
        $refreshOrder = new RefreshOrder(new ShippingFeeCalculator());
        DB::transaction(static fn() => $refreshOrder->handle($order));
        return $refreshOrder->totalHasChanged();
    }

    protected function checkProductStocks(Order $order): bool
    {
        $products = $order->products;
        $products->loadMissing('parent');
        foreach ($products as $product) {
            if (!$product->canBeBought($product->pivot->quantity)) {
                return false;
            }
        }

        return true;
    }
}