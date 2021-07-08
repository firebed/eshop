<?php

namespace Eshop;

use Eshop\Livewire\Customer\Account\EditProfile;
use Eshop\Livewire\Customer\Checkout\CartButton;
use Eshop\Livewire\Customer\Checkout\EditCheckoutDetails;
use Eshop\Livewire\Customer\Checkout\EditCheckoutPayment;
use Eshop\Livewire\Customer\Checkout\ShowCheckoutProducts;
use Eshop\Livewire\Customer\Product\AddToCartForm;
use Eshop\Livewire\Customer\Product\ProductVariants;
use Eshop\Livewire\Customer\Product\ProductVariantsButtons;
use Eshop\Livewire\Dashboard\Analytics\ShowAnalyticsDashboard;
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
use Eshop\Livewire\Dashboard\Config\ShowLocales;
use Eshop\Livewire\Dashboard\Config\ShowUnits;
use Eshop\Livewire\Dashboard\Config\ShowVats;
use Eshop\Livewire\Dashboard\Intl\ShowCountries;
use Eshop\Livewire\Dashboard\Intl\ShowPaymentMethods;
use Eshop\Livewire\Dashboard\Intl\ShowShippingMethods;
use Eshop\Livewire\Dashboard\Product\CreateProduct;
use Eshop\Livewire\Dashboard\Product\CreateProductGroup;
use Eshop\Livewire\Dashboard\Product\EditProduct;
use Eshop\Livewire\Dashboard\Product\EditProductGroup;
use Eshop\Livewire\Dashboard\Product\ShowManufacturers;
use Eshop\Livewire\Dashboard\Product\ShowProductImages;
use Eshop\Livewire\Dashboard\Product\ShowProducts;
use Eshop\Livewire\Dashboard\Product\ShowTrashedProducts;
use Eshop\Livewire\Dashboard\Product\ShowVariants;
use Eshop\Livewire\Dashboard\Product\VariantTypes;
use Eshop\Livewire\Dashboard\User\ShowUserPermissions;
use Eshop\Livewire\Dashboard\User\ShowUsers;
use Eshop\Livewire\Dashboard\User\UserAddressesTable;
use Eshop\Livewire\Dashboard\User\UserCartsTable;
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
        Livewire::component('customer.product.product-variants', ProductVariants::class);
        Livewire::component('customer.product.product-variants-buttons', ProductVariantsButtons::class);
        Livewire::component('customer.product.add-to-cart-form', AddToCartForm::class);
        Livewire::component('customer.checkout.cart-button', CartButton::class);
        Livewire::component('customer.checkout.show-checkout-products', ShowCheckoutProducts::class);
        Livewire::component('customer.checkout.edit-checkout-details', EditCheckoutDetails::class);
        Livewire::component('customer.checkout.edit-checkout-payment', EditCheckoutPayment::class);

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

        Livewire::component('dashboard.product.show-manufacturers', ShowManufacturers::class);

        // Intl
        Livewire::component('dashboard.intl.show-countries', ShowCountries::class);
        Livewire::component('dashboard.intl.show-payment-methods', ShowPaymentMethods::class);
        Livewire::component('dashboard.intl.show-shipping-methods', ShowShippingMethods::class);

        // Products
        Livewire::component('dashboard.product.create-product', CreateProduct::class);
        Livewire::component('dashboard.product.edit-product', EditProduct::class);
        Livewire::component('dashboard.product.show-product-images', ShowProductImages::class);
        Livewire::component('dashboard.product.show-products', ShowProducts::class);
        Livewire::component('dashboard.product.show-trashed-products', ShowTrashedProducts::class);
        Livewire::component('dashboard.product.show-variants', ShowVariants::class);
        Livewire::component('dashboard.product.variant-types', VariantTypes::class);

        // User
        Livewire::component('dashboard.user.show-users', ShowUsers::class);
        Livewire::component('dashboard.user.user-addresses-table', UserAddressesTable::class);
        Livewire::component('dashboard.user.user-carts-table', UserCartsTable::class);

        // User permissions
        Livewire::component('dashboard.user.show-user-permissions', ShowUserPermissions::class);

        // Analytics

        // Config
        Livewire::component('dashboard.config.show-vats', ShowVats::class);
        Livewire::component('dashboard.config.show-locales', ShowLocales::class);
        Livewire::component('dashboard.config.show-units', ShowUnits::class);
    }
}