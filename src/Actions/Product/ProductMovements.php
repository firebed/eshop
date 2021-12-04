<?php

namespace Eshop\Actions\Product;

use Eshop\Models\Cart\CartProduct;
use Eshop\Models\Product\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductMovements
{
    public function handle(Product $product): Builder
    {
        return CartProduct::select('carts.submitted_at', 'cart_product.*')
            ->leftJoin('carts', 'carts.id', '=', 'cart_product.cart_id')
            ->where('cart_product.product_id', $product->id)
            ->whereNotNull('carts.submitted_at')
            ->latest('submitted_at')
            ->with('cart.shippingAddress', 'cart.status');
    }
}