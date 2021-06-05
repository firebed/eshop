<?php

namespace Eshop;

use Eshop\Livewire\Customer\Checkout\CartButton;
use Eshop\Livewire\Dashboard\Product\CreateProduct;
use Eshop\Livewire\Dashboard\Product\CreateProductGroup;
use Eshop\Livewire\Dashboard\Product\EditProduct;
use Eshop\Livewire\Dashboard\Product\EditProductGroup;
use Eshop\Livewire\Dashboard\Product\ShowProducts;
use Eshop\Livewire\Dashboard\Product\VariantTypes;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        Livewire::component('customer.checkout.cart-button', CartButton::class);

        // Products
        Livewire::component('dashboard.product.create-product', CreateProduct::class);
        Livewire::component('dashboard.product.create-product-group', CreateProductGroup::class);
        Livewire::component('dashboard.product.edit-product', EditProduct::class);
        Livewire::component('dashboard.product.edit-product-group', EditProductGroup::class);
        Livewire::component('dashboard.product.show-products', ShowProducts::class);
        Livewire::component('dashboard.product.variant-types', VariantTypes::class);
    }
}