<?php


namespace Eshop\Models\Cart\Concerns;


use Eshop\Actions\Order\PaymentFeeCalculator;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Models\Product\Product;
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

    private function createIfNotExists(): void
    {
        if ($this->exists) {
            return;
        }

        $this->user()->associate(auth()->user());
        if (Auth::guest()) {
            $this->cookie_id = (string)Str::uuid();
        }

        $this->channel = 'eshop';
        $this->ip = request()?->ip();

        if ($this->save()) {
            if ($this->cookie_id !== NULL) {
                session()->put('cart-session-id', $this->id);
                cookie()->queue('cart-cookie-id', $this->cookie_id, now()->addMonths(2)->diffInMinutes());
            }

            $location = Location::get(request()?->ip());
            $country = $location ? Country::code($location->countryCode)->first() : Country::default();

            $shippingAddress = new Address(['cluster' => 'shipping']);

            if (auth()->user() && auth()->user()->addresses->isNotEmpty()) {
                $userAddress = auth()->user()->addresses->first();
                $shippingAddress = $userAddress->replicate(['addressable_type', 'addressable_id']);
                $shippingAddress->cluster = 'shipping';
                $shippingAddress->related_id = $userAddress->id;
            } else {
                $shippingAddress->country()->associate($country);
            }

            $this->shippingAddress()->save($shippingAddress);
        }
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

    public function updateQuantity(Product|int $product, int $quantity): void
    {
        $this->updateProduct($product, ['quantity' => $quantity]);
    }

    public function updateProduct(Product|int $product, array $attributes): void
    {
        $this->products()->updateExistingPivot($product, $attributes);

        if ($this->relationLoaded('products')) {
//            $this->products->find($product)->pivot->fill($attributes);
            $this->unsetRelation('products');
        }
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

        $this->updateTotalWeight();

        return $isDirty;
    }

    public function updateTotalWeight(): void
    {
        $this->parcel_weight = $this->products->sum(fn($p) => $p->weight * $p->pivot->quantity);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function isEmpty(): bool
    {
        return !$this->exists || $this->products->isEmpty();
    }

    public function updateTotal(): void
    {
        $this->total = $this->products_value + $this->total_fees;
    }

//    public function updateFees($preferredShippingMethodId = NULL, $preferredPaymentMethodId = NULL): void
//    {
//        $this->updateShippingFee($preferredShippingMethodId);
//        $this->updatePaymentFee($preferredPaymentMethodId);
//    }
//
//    public function updateShippingFee(int $preferredShippingMethodId = NULL): void
//    {
//        $country = $this->shippingAddress->country ?? NULL;
//        if ($country === NULL) {
//            return;
//        }
//
//        $calculator = new ShippingFeeCalculator();
//        [$method, $fee] = $calculator->handle($country, $this->products_value, $this->parcel_weight, $this->shippingAddress->postcode, $preferredShippingMethodId);
//
//        $this->shippingMethod()->associate($method);
//        $this->shipping_fee = $fee ?: 0;
//    }

    public function updatePaymentFee(int $preferredPaymentMethodId = NULL): void
    {
        $country = $this->shippingAddress->country ?? NULL;
        if ($country === NULL) {
            return;
        }

        $calculator = new PaymentFeeCalculator();
        [$method, $fee] = $calculator->handle($country, $this->products_value, $preferredPaymentMethodId);

        $this->paymentMethod()->associate($method);
        $this->payment_fee = $fee ?: 0;
    }

    public function getProductQuantity(Product|int $product): int
    {
        $productId = $product instanceof Product ? $product->id : $product;

        if ($this->products->contains($productId)) {
            return $this->products->find($productId)->pivot->quantity;
        }

        return 0;
    }

    public function pluckProductQuantities(): Collection
    {
        return $this->products->pluck('pivot.quantity', 'pivot.product_id');
    }

    public function getProductsValueAttribute(): float
    {
        return $this->products->sum('pivot.total');
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
}
