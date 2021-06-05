<?php

namespace Eshop;

use Eshop\Livewire\Customer\Checkout\CartButton;
use Eshop\Livewire\Dashboard\Cart\BillingAddress;
use Eshop\Livewire\Dashboard\Cart\CartHeader;
use Eshop\Livewire\Dashboard\Cart\CartItemCreateModal;
use Eshop\Livewire\Dashboard\Cart\CartOverview;
use Eshop\Livewire\Dashboard\Cart\CustomerNotes;
use Eshop\Livewire\Dashboard\Cart\Invoice;
use Eshop\Livewire\Dashboard\Cart\ShippingAddress;
use Eshop\Livewire\Dashboard\Cart\ShowCart;
use Eshop\Livewire\Dashboard\Cart\ShowCarts;
use Eshop\Livewire\Dashboard\Cart\StatusesList;
use Eshop\Livewire\Dashboard\Category\ShowCategories;
use Eshop\Livewire\Dashboard\Category\ShowCategoryProperties;
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

        // Cart
        Livewire::component('dashboard.cart.billing-address', BillingAddress::class);
        Livewire::component('dashboard.cart.cart-header', CartHeader::class);
        Livewire::component('dashboard.cart.cart-item-create-modal', CartItemCreateModal::class);
        Livewire::component('dashboard.cart.cart-overview', CartOverview::class);
        Livewire::component('dashboard.cart.customer-notes', CustomerNotes::class);
        Livewire::component('dashboard.cart.invoice', Invoice::class);
        Livewire::component('dashboard.cart.shipping-address', ShippingAddress::class);
        Livewire::component('dashboard.cart.show-cart', ShowCart::class);
        Livewire::component('dashboard.cart.show-carts', ShowCarts::class);
        Livewire::component('dashboard.cart.statuses-list', StatusesList::class);

        // Categories
        Livewire::component('dashboard.category.show-categories', ShowCategories::class);
        Livewire::component('dashboard.category.show-category-properties', ShowCategoryProperties::class);

        // Products
        Livewire::component('dashboard.product.create-product', CreateProduct::class);
        Livewire::component('dashboard.product.create-product-group', CreateProductGroup::class);
        Livewire::component('dashboard.product.edit-product', EditProduct::class);
        Livewire::component('dashboard.product.edit-product-group', EditProductGroup::class);
        Livewire::component('dashboard.product.show-products', ShowProducts::class);
        Livewire::component('dashboard.product.variant-types', VariantTypes::class);
    }
}