<?php

namespace App\Http\Livewire\Checkout\Concerns;


use Eshop\Actions\Order\FindShippingMethodForOrder;
use Eshop\Actions\Order\RefreshOrder;
use Eshop\Actions\Order\ShippingFeeCalculator;
use Eshop\Models\Product\Product;
use Eshop\Repository\Contracts\Order;

trait ControlsOrder
{
//    protected function refreshOrder(Order $order): void
//    {
//        $order->refreshProducts();
//        $this->updateTotal($order);
//    }

    protected function updateTotal(Order $order): void
    {
        $refresh = new RefreshOrder(new ShippingFeeCalculator());
        $refresh->handle($order);
//        $countryShippingMethod = session('countryShippingMethod');
//        $countryPaymentMethod = session('countryPaymentMethod');
//        
//        $country = $order->shippingAddress->country ?? null;
//        if ($country === null) {
//            $order->shippingMethod()->disassociate();
//            $order->shipping_fee = 0;
//        } else {
//            $countryShippingMethod = (new FindShippingMethodForOrder())->handle($country, $order->products_value, $countryShippingMethod);
//            if ($countryShippingMethod) {
//                $order->shippingMethod()->associate($countryShippingMethod->shipping_method_id);
//                $order->shipping_fee = (new ShippingFeeCalculator())->handle($countryShippingMethod, $order->parcel_weight, $order->shippingAddress->postcode);
//            } else {
//                $order->shippingMethod()->disassociate();
//                $order->shipping_fee = 0;
//            }
//        }
//
//        session()->put('countryShippingMethod', $countryShippingMethod?->id);
//        
//        $order->updateTotalWeight();
////        $order->updateFees($preferredShippingMethodId, $preferredPaymentMethodId);
//        $order->updatePaymentFee($countryPaymentMethod);
//        $order->updateTotal();
//        $order->save();
    }
//
//    protected function softRefreshOrder(Order $order): void
//    {
//        $order->refreshProducts();
//        $this->updateTotal($order);
//    }

    protected function addProduct(Order $order, Product $product, int $quantity): bool
    {
        $order->addProduct($product, $quantity);
        $this->updateTotal($order);
        return true;
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
}
