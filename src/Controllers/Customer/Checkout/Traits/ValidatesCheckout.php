<?php

namespace Eshop\Controllers\Customer\Checkout\Traits;

use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Models\Cart\CartEvent;
use Eshop\Repository\Contracts\Order;
use Illuminate\Support\Facades\DB;

trait ValidatesCheckout
{
    private RefreshOrder $refresh;
    
    protected function validateCheckout(Order $order): bool
    {
        if ($order->isEmpty() || $order->isSubmitted()) {
            return false;
        }

        if ($this->totalHasChanged($order)) {
            session()->flash('products-values-changed');
            CartEvent::checkoutTotalUpdated($order->id);
            return false;
        }
                
        if (!$this->checkProductStocks($order)) {
            session()->flash('insufficient-quantity');
            CartEvent::checkoutInsufficientQuantity($order->id);
            return false;
        }

        return true;
    }

    protected function processingFeesHasChanged(): bool
    {
        return $this->refresh->processingFeesHasChanged();
    }

    protected function validateShippingAddress(Order $order): bool
    {
        return $order->shippingAddress !== null && $order->shippingAddress->isFilled();
    }

    protected function totalHasChanged(Order $order): bool
    {
        $this->refresh = new RefreshOrder(new ShippingFeeCalculator());
        DB::transaction(fn() => $this->refresh->handle($order));
        return $this->refresh->productsTotalHasChanged();
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