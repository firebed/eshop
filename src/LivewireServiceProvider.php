<?php

namespace Eshop;

use Eshop\Livewire\Customer\Checkout\CartButton;
use Eshop\Livewire\Customer\Checkout\ShowCheckoutProducts;
use Eshop\Livewire\Customer\Product\AddToCartForm;
use Eshop\Livewire\Customer\Product\ProductVariants;
use Eshop\Livewire\Customer\Product\ProductVariantsButtons;
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
use Eshop\Livewire\Dashboard\Category\CategoriesTree;
use Eshop\Livewire\Dashboard\Category\CategoryPropertyChoices;
use Eshop\Livewire\Dashboard\Config\ShowLocales;
use Eshop\Livewire\Dashboard\Config\ShowUnits;
use Eshop\Livewire\Dashboard\Config\ShowVats;
use Eshop\Livewire\Dashboard\Intl\CountryPaymentMethods;
use Eshop\Livewire\Dashboard\Intl\CountryShippingMethods;
use Eshop\Livewire\Dashboard\Intl\InaccessibleAreas;
use Eshop\Livewire\Dashboard\Pos\PosInvoice;
use Eshop\Livewire\Dashboard\Pos\PosModels;
use Eshop\Livewire\Dashboard\Pos\PosPayment;
use Eshop\Livewire\Dashboard\Pos\PosProducts;
use Eshop\Livewire\Dashboard\Pos\PosProductsSearch;
use Eshop\Livewire\Dashboard\Pos\PosShipping;
use Eshop\Livewire\Dashboard\Product\ProductProperties;
use Eshop\Livewire\Dashboard\Product\ShowManufacturers;
use Eshop\Livewire\Dashboard\Product\ShowProductImages;
use Eshop\Livewire\Dashboard\Product\ShowProducts;
use Eshop\Livewire\Dashboard\Product\ShowTrashedProducts;
use Eshop\Livewire\Dashboard\Product\VariantBulkCreateTable;
use Eshop\Livewire\Dashboard\Product\VariantsTable;
use Eshop\Livewire\Dashboard\Product\VariantTypes;
use Eshop\Livewire\Dashboard\Slide\ShowSlides;
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
        Livewire::component('dashboard.category.property-choices', CategoryPropertyChoices::class);
        Livewire::component('dashboard.category.categories-tree', CategoriesTree::class);

        Livewire::component('dashboard.product.show-manufacturers', ShowManufacturers::class);

        // Intl
        Livewire::component('dashboard.intl.country-payment-methods', CountryPaymentMethods::class);
        Livewire::component('dashboard.intl.country-shipping-methods', CountryShippingMethods::class);
        Livewire::component('dashboard.shipping-methods.inaccessible-areas', InaccessibleAreas::class);

        // Products
        Livewire::component('dashboard.product.show-product-images', ShowProductImages::class);
        Livewire::component('dashboard.product.show-products', ShowProducts::class);
        Livewire::component('dashboard.product.show-trashed-products', ShowTrashedProducts::class);
        Livewire::component('dashboard.product.variants-table', VariantsTable::class);
        Livewire::component('dashboard.product.product-properties', ProductProperties::class);
        Livewire::component('dashboard.product.variant-types', VariantTypes::class);
        Livewire::component('dashboard.variant.variant-bulk-create', VariantBulkCreateTable::class);

        // User
        Livewire::component('dashboard.user.show-users', ShowUsers::class);
        Livewire::component('dashboard.user.user-addresses-table', UserAddressesTable::class);
        Livewire::component('dashboard.user.user-carts-table', UserCartsTable::class);

        // User permissions
        Livewire::component('dashboard.user.show-user-permissions', ShowUserPermissions::class);

        // Pos
        Livewire::component('dashboard.pos.models', PosModels::class);
        Livewire::component('dashboard.pos.products', PosProducts::class);
        Livewire::component('dashboard.pos.shipping', PosShipping::class);
        Livewire::component('dashboard.pos.payment', PosPayment::class);
        Livewire::component('dashboard.pos.invoice', PosInvoice::class);
        Livewire::component('dashboard.pos.products-search', PosProductsSearch::class);

        // Slides
        Livewire::component('dashboard.slide.show-slides', ShowSlides::class);

        // Analytics

        // Config
        Livewire::component('dashboard.config.show-vats', ShowVats::class);
        Livewire::component('dashboard.config.show-locales', ShowLocales::class);
        Livewire::component('dashboard.config.show-units', ShowUnits::class);
    }
}