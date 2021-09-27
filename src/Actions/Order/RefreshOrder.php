<?php

namespace Eshop\Actions\Order;

use Eshop\Models\Location\Country;
use Eshop\Repository\Contracts\Order;

class RefreshOrder
{
    private Order $order;
    private bool  $totalHasChanged = false;
    private ShippingFeeCalculator $shippingFeeCalculator;

    public function __construct(ShippingFeeCalculator $shippingFeeCalculator)
    {
        $this->shippingFeeCalculator = $shippingFeeCalculator;
    }
    
    public function handle(Order $order): void
    {
        $this->order = $order;
        $currentTotal = (float) $order->total;

        $this->refreshProducts();

        $country = $order->shippingAddress->country ?? null;
        $this->updateShipping($country);
        $this->updatePayment($country);

        $order->parcel_weight = $this->calculateTotalWeight();
        $order->total = $this->calculateTotal();
        $order->save();

        $this->totalHasChanged = abs($currentTotal - $order->total) < PHP_FLOAT_EPSILON;
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
            session()->put('countryShippingMethod');
            return;
        }

        $countryShippingMethod = session('countryShippingMethod');
        $option = $shippingOptions->firstWhere('id', $countryShippingMethod);
        if ($option === null) {
            $option = $shippingOptions->first(); // Fallback
        }
        
        session()->put('countryShippingMethod', $option->id);
        $this->order->shippingMethod()->associate($option->shipping_method_id);
        $this->order->shipping_fee = $this->shippingFeeCalculator->handle($option, $this->order->parcel_weight, $this->order->shippingAddress->postcode);
    }

    private function updatePayment(?Country $country): void
    {
        if ($country === null || ($paymentOptions = $country->filterPaymentOptions($this->order->products_value))->isEmpty()) {
            $this->order->paymentMethod()->disassociate();
            $this->order->payment_fee = 0;
            session()->put('countryPaymentMethod');
            return;
        }

        $countryPaymentMethod = session('countryPaymentMethod');
        $option = $paymentOptions->firstWhere('id', $countryPaymentMethod);
        if ($option === null) {
            $option = $paymentOptions->first(); // Fallback
        }

        session()->put('countryPaymentMethod', $option->id);
        $this->order->paymentMethod()->associate($option->payment_method_id);
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