<?php


namespace Ecommerce\Models\Cart\Concerns;


use App\Models\Location\Address;
use App\Models\Location\Country;
use App\Models\Product\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

trait ImplementsOrder
{
    public function addProduct(Product|int $product, int $quantity): void
    {
        $this->createIfNotExists();

        $productId = $product instanceof Product ? $product->id : $product;

        $this->products()->syncWithoutDetaching([$productId => $this->mapProduct($product, $quantity)]);

        if ($this->relationLoaded('products')) {
            $this->unsetRelation('products');
        }
    }

    public function syncProducts(mixed $ids): void
    {
        $this->createIfNotExists();

        $products = Product::findMany($ids->keys());
        $sync = $ids
            ->map(function ($quantity, $productId) use ($products) {
                $product = $products->find($productId);
                return $product ? $this->mapProduct($product, $quantity) : NULL;
            })
            ->filter()
            ->all();

        $this->products()->syncWithoutDetaching($sync);

        if ($this->relationLoaded('products')) {
            $this->unsetRelation('products');
        }
    }

    public function updateProduct(Product|int $product, array $attributes): void
    {
        $this->products()->updateExistingPivot($product, $attributes);

        if ($this->relationLoaded('products')) {
//            $this->products->find($product)->pivot->fill($attributes);
            $this->unsetRelation('products');
        }
    }

    public function updateQuantity(Product|int $product, int $quantity): void
    {
        $this->updateProduct($product, ['quantity' => $quantity]);
    }

    public function removeProduct($ids): void
    {
        $this->products()->detach($ids);

        if ($this->relationLoaded('products')) {
            $this->unsetRelation('products');
        }
    }

    public function refreshProducts(): bool
    {
        $isDirty = FALSE;
        foreach ($this->products as $product) {
            $product->pivot->fill($product->only('price', 'compare_price', 'discount', 'vat'));
            $isDirty = !$isDirty && $product->pivot->isDirty();
            $product->pivot->save();
        }

        $this->parcel_weight = $this->products->sum(fn($p) => $p->weight * $p->pivot->quantity);

        return $isDirty;
    }

    public function isEmpty(): bool
    {
        return !$this->exists || $this->products->isEmpty();
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function updateTotalWeight(): void
    {
        $this->parcel_weight = $this->products->sum(fn($p) => $p->weight * $p->pivot->quantity);
    }

    public function updateTotal(): void
    {
        $this->total = $this->products_value + $this->total_fees;
    }

    public function updateFees($preferredShippingMethodId = NULL, $preferredPaymentMethodId = NULL): void
    {
        $this->updateShippingFee($preferredShippingMethodId);
        $this->updatePaymentFee($preferredPaymentMethodId);
    }

    public function updateShippingFee(int $preferredShippingMethodId = NULL): void
    {
        $country = $this->shippingAddress->country ?? NULL;
        if ($country === NULL) {
            return;
        }

        $shippingMethods = $country->filterShippingOptions($this->products_value);

        if ($shippingMethods->isEmpty()) {
            $this->shippingMethod()->disassociate();
            $this->shipping_fee = 0;
            return;
        }

        $suggestedShippingMethod = $shippingMethods->first();
        $suggestedShippingFee = $suggestedShippingMethod->calculateTotalFee($this->parcel_weight);

        $this->shippingMethod()->associate($suggestedShippingMethod->shipping_method_id);
        $this->shipping_fee = $suggestedShippingFee;

        if ($preferredShippingMethodId === NULL) {
            return;
        }

        $preferredShippingMethod = $shippingMethods->firstWhere('shipping_method_id', $preferredShippingMethodId);
        if ($preferredShippingMethod !== NULL && $preferredShippingMethod !== $suggestedShippingMethod) {
            $preferredShippingFee = $preferredShippingMethod->calculateTotalFee($this->parcel_weight);
            $this->shippingMethod()->associate($preferredShippingMethod->shipping_method_id);
            $this->shipping_fee = $preferredShippingFee;
        }
    }

    public function updatePaymentFee(int $preferredPaymentMethodId = NULL): void
    {
        $country = $this->shippingAddress->country ?? NULL;
        if ($country === NULL) {
            return;
        }

        $paymentMethods = $country->filterPaymentMethods($this->products_value);

        if ($paymentMethods->isEmpty()) {
            $this->paymentMethod()->disassociate();
            $this->payment_fee = 0;
            return;
        }

        $suggestedPaymentMethod = $paymentMethods->first();
        $suggestedPaymentFee = $suggestedPaymentMethod->calculateTotalFee();

        $this->paymentMethod()->associate($suggestedPaymentMethod->payment_method_id);
        $this->payment_fee = $suggestedPaymentFee;

        if ($preferredPaymentMethodId === NULL) {
            return;
        }

        $preferredPaymentMethod = $paymentMethods->firstWhere('payment_method_id', $preferredPaymentMethodId);

        if ($preferredPaymentMethod !== NULL && $preferredPaymentMethod !== $suggestedPaymentMethod) {
            $preferredPaymentFee = $preferredPaymentMethod->calculateTotalFee();
            $this->paymentMethod()->associate($preferredPaymentMethod->payment_method_id);
            $this->payment_fee = $preferredPaymentFee;
        }
    }

    public function getProductQuantity(Product|int $product): int
    {
        $productId = $product instanceof Product ? $product->id : $product;

        if ($this->products->contains($productId)) {
            return $this->products->find($productId)->pivot->quantity;
        }

        return 0;
    }

    public function submit(): void
    {
        $this->status()->associate(1);
        $this->submitted_at = now();
        $this->save();

        session()->forget('cart-session-id');
        cookie()->queue(cookie()->forget('cart-cookie-id'));

//        event(new CartSubmitted($this));
    }

    public function pluckProductQuantities(): Collection
    {
        return $this->products->pluck('pivot.quantity', 'pivot.product_id');
    }

    public function getProductsValueAttribute(): float
    {
        return $this->products->sum('pivot.netValue');
    }

    public function getTotalQuantityAttribute(): float
    {
        return $this->products->sum('pivot.quantity');
    }

    public function getTotalFeesAttribute(): float
    {
        return $this->shipping_fee + $this->payment_fee;
    }

    public function getTotalWithoutFeesAttribute(): float
    {
        return $this->total - $this->total_fees;
    }

    private function mapProduct($product, $quantity): array
    {
        return [
            'quantity'      => $quantity,
            'price'         => $product->price,
            'compare_price' => $product->compare_price,
            'discount'      => $product->discount,
            'vat'           => $product->vat,
            'deleted_at'    => NULL
        ];
    }

    private function createIfNotExists(): void
    {
        if ($this->exists) {
            return;
        }

        $this->user()->associate(auth()->user());
        if (Auth::guest()) {
            $this->cookie_id = (string)Str::uuid();
        }

        if ($this->save()) {
            if ($this->cookie_id !== NULL) {
                session()->put('cart-session-id', $this->id);
                cookie()->queue('cart-cookie-id', $this->cookie_id, now()->addMonths(2)->diffInMinutes());
            }

            $location = Location::get();
            $country = Country::code($location->countryCode)->first() ?? Country::default();

            $shippingAddress = new Address(['cluster' => 'shipping']);
            $shippingAddress->country()->associate($country);
            $this->shippingAddress()->save($shippingAddress);
        }
    }
}
