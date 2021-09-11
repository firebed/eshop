<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Location\Country;
use Eshop\Repository\Contracts\Order;

class RefreshOrder
{
    public ShippingFeeCalculator       $shippingFeeCalculator;
    private Order                      $order;
    private FindShippingMethodForOrder $findShippingMethodForOrder;
    private PaymentFeeCalculator       $paymentFeeCalculator;
    private bool                       $totalHasChanged = false;

    public function __construct(FindShippingMethodForOrder $findShippingMethodForOrder, ShippingFeeCalculator $shippingFeeCalculator, PaymentFeeCalculator $paymentFeeCalculator)
    {
        $this->findShippingMethodForOrder = $findShippingMethodForOrder;
        $this->shippingFeeCalculator = $shippingFeeCalculator;
        $this->paymentFeeCalculator = $paymentFeeCalculator;
    }

    public function handle(Order $order): void
    {
        $this->order = $order;
        $currentTotal = $order->total;

        $this->refreshProducts();

        $country = $order->shippingAddress->country ?? null;
        $this->updateShipping($country);
        $this->updatePayment($country);

        $order->parcel_weight = $this->calculateTotalWeight();
        $order->total = $this->calculateTotal();
        $order->save();

        $this->totalHasChanged = $order->total !== $currentTotal;
    }

    public function totalHasChanged(): bool
    {
        return $this->totalHasChanged;
    }

    private function updateShipping(?Country $country): void
    {
        if ($country === null || ($shippingOptions = $country->filterShippingOptions($this->order->products_value))->isEmpty()) {
            $this->order->shippingMethod()->disassociate();
            $this->order->shipping_fee = 0;
            return;
        }

        $option = $shippingOptions->firstWhere('id', $this->order->shipping_method_id);
        if ($option === null) {
            $option = $shippingOptions->first(); // Fallback
        }

        $this->order->shippingMethod()->associate($option);
        $this->order->shipping_fee = $option ? $this->shippingFeeCalculator->handle($option, $this->order->parcel_weight, $this->order->shippingAddress->postcode) : 0;
    }

    private function updatePayment(?Country $country): void
    {
        if ($country === null || ($paymentOptions = $country->filterPaymentOptions($this->order->products_value))->isEmpty()) {
            $this->order->paymentMethod()->disassociate();
            $this->order->payment_fee = 0;
            return;
        }

        $option = $paymentOptions->firstWhere('id', $this->order->payment_method_id);
        if ($option === null) {
            $option = $paymentOptions->first(); // Fallback
        }

        $this->order->paymentMethod()->associate($option);
        $this->order->payment_fee = $option->fee;
    }

    private function refreshProducts(): void
    {
        $isDirty = false;
        foreach ($this->order->products as $product) {
            $product->pivot->fill($product->only('price', 'compare_price', 'discount', 'vat'));
            $isDirty = !$isDirty && $product->pivot->isDirty();
            $product->pivot->save();
        }
    }

    private function calculateTotal(): float
    {
        return $this->order->products_value + $this->order->shipping_fee + $this->order->payment_fee;
    }

    private function calculateTotalWeight(): int
    {
        return $this->order->products->sum(fn($p) => $p->weight * $p->pivot->quantity);
    }
}