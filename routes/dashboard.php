<?php

use Eshop\Controllers\Customer\Product\LabelPrintController;
use Eshop\Controllers\Customer\ThemeController;
use Eshop\Controllers\Dashboard\Analytics\AnalyticsController;
use Eshop\Controllers\Dashboard\Analytics\CourierAnalyticsController;
use Eshop\Controllers\Dashboard\Analytics\OrderAnalyticsController;
use Eshop\Controllers\Dashboard\Analytics\WarehouseAnalyticsController;
use Eshop\Controllers\Dashboard\Cart\CartController;
use Eshop\Controllers\Dashboard\Cart\CartInvoiceController;
use Eshop\Controllers\Dashboard\Cart\CartPrintVoucherController;
use Eshop\Controllers\Dashboard\Cart\OrderPrintController;
use Eshop\Controllers\Dashboard\Category\CategoryController;
use Eshop\Controllers\Dashboard\Category\CategoryPropertyController;
use Eshop\Controllers\Dashboard\ChannelController;
use Eshop\Controllers\Dashboard\Intl\BulkProvinceController;
use Eshop\Controllers\Dashboard\Intl\CountryController;
use Eshop\Controllers\Dashboard\Intl\CountryPaymentMethodController;
use Eshop\Controllers\Dashboard\Intl\CountryShippingMethodController;
use Eshop\Controllers\Dashboard\Intl\PaymentMethodController;
use Eshop\Controllers\Dashboard\Intl\ProvinceController;
use Eshop\Controllers\Dashboard\Intl\ShippingMethodController;
use Eshop\Controllers\Dashboard\Invoice\ClientController;
use Eshop\Controllers\Dashboard\Invoice\InvoiceController;
use Eshop\Controllers\Dashboard\Invoice\InvoiceTransmissionController;
use Eshop\Controllers\Dashboard\NotificationController;
use Eshop\Controllers\Dashboard\Page\PageController;
use Eshop\Controllers\Dashboard\Pos\PosController;
use Eshop\Controllers\Dashboard\Product\CollectionController;
use Eshop\Controllers\Dashboard\Product\ManufacturerController;
use Eshop\Controllers\Dashboard\Product\ProductAuditController;
use Eshop\Controllers\Dashboard\Product\ProductBarcodeController;
use Eshop\Controllers\Dashboard\Product\ProductController;
use Eshop\Controllers\Dashboard\Product\ProductCopyController;
use Eshop\Controllers\Dashboard\Product\ProductImageController;
use Eshop\Controllers\Dashboard\Product\ProductMovementController;
use Eshop\Controllers\Dashboard\Product\ProductTranslationController;
use Eshop\Controllers\Dashboard\Product\ProductTrashController;
use Eshop\Controllers\Dashboard\Product\ProductVisibilityController;
use Eshop\Controllers\Dashboard\Product\VariantBulkController;
use Eshop\Controllers\Dashboard\Product\VariantBulkImageController;
use Eshop\Controllers\Dashboard\Product\VariantController;
use Eshop\Controllers\Dashboard\SidebarController;
use Eshop\Controllers\Dashboard\Simplify\SimplifyController;
use Eshop\Controllers\Dashboard\Simplify\SimplifyWebhookController;
use Eshop\Controllers\Dashboard\Skroutz\SkroutzWebhookController;
use Eshop\Controllers\Dashboard\Slide\SlideController;
use Eshop\Controllers\Dashboard\User\UserController;
use Eshop\Controllers\Dashboard\User\UserPermissionController;
use Eshop\Controllers\Dashboard\User\UserVariableController;
use Illuminate\Support\Facades\Route;

Route::post('simplify/webhook', SimplifyWebhookController::class)->middleware('web');

if (eshop('skroutz')) {
    Route::post('webhooks/skroutz', SkroutzWebhookController::class)->name('webhooks.skroutz');
}

Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::post('simplify/checkout', [SimplifyController::class, 'checkout'])->name('simplify.checkout');
        Route::get('simplify', [SimplifyController::class, 'index'])->name('simplify.index');

        Route::put('sidebar', SidebarController::class);
        Route::put('theme', ThemeController::class);

        Route::get('products/{product}/movements', ProductMovementController::class)->name('products.movements.index');
        Route::get('products/{product}/images', [ProductImageController::class, 'index'])->name('products.images.index');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->withTrashed();
        Route::post('products/{product}/visibility', ProductVisibilityController::class)->name('products.toggle-visibility');
        Route::post('products/barcode', ProductBarcodeController::class)->name('products.barcode.create');
        Route::get('products/trashed', ProductTrashController::class)->name('products.trashed.index');
        Route::resource('products.audits', ProductAuditController::class)->shallow()->only('index', 'show');
        Route::get('products/{product}/translations', ProductTranslationController::class)->name('products.translations.edit');
        Route::resource('products.copy', ProductCopyController::class)->only('create', 'store')->shallow();
        Route::resource('products', ProductController::class)->except('show', 'edit');

        Route::put('variants/images', VariantBulkImageController::class)->name('variants.bulk-images.update');

        Route::prefix('products/{product}/variants')->as('variants.')->group(function () {
            Route::get('bulk-create', [VariantBulkController::class, 'create'])->name('bulk-create');
            Route::post('bulk-create', [VariantBulkController::class, 'store'])->name('bulk-store');
            Route::get('bulk-edit', [VariantBulkController::class, 'edit'])->name('bulk-edit');
            Route::put('bulk-edit', [VariantBulkController::class, 'update'])->name('bulk-update');
            Route::delete('bulk-destroy', [VariantBulkController::class, 'destroy'])->name('bulk-destroy');
        });

        if (eshop('skroutz')) {
            Route::resource('channels', ChannelController::class);
        }

        Route::resource('products.variants', VariantController::class)->shallow()->except('show');

        Route::get('labels', [LabelPrintController::class, 'index'])->name('labels.index');
        Route::post('labels', [LabelPrintController::class, 'export'])->name('labels.export');

        Route::delete('collections/{collection}/detach-product/{product}', [CollectionController::class, 'detachProduct'])->name('collections.detachProduct');
        Route::delete('collections/destroy-many', [CollectionController::class, 'destroyMany'])->name('collections.destroyMany');
        Route::resource('collections', CollectionController::class)->except('show');

        Route::get('carts/{cart}/invoice', CartInvoiceController::class)->name('carts.invoice');
        Route::get('carts/{cart}/print-voucher', CartPrintVoucherController::class)->name('carts.print-voucher');
        Route::get('carts/{cart}/print', OrderPrintController::class)->name('carts.print');
        Route::resource('carts', CartController::class)->only('index', 'show', 'destroy');

        Route::put('categories/properties/{property}/moveUp', [CategoryPropertyController::class, 'moveUp'])->name('categories.properties.moveUp');
        Route::put('categories/properties/{property}/moveDown', [CategoryPropertyController::class, 'moveDown'])->name('categories.properties.moveDown');
        Route::resource('categories.properties', CategoryPropertyController::class)->only('create', 'store');
        Route::resource('categories/properties', CategoryPropertyController::class, ['as' => 'categories'])->only('edit', 'update', 'destroy');
        Route::get('categories/expand/{category?}', [CategoryController::class, 'expand'])->name('categories.expand');
        Route::post('categories/move', [CategoryController::class, 'move'])->name('categories.move');
        Route::delete('categories/destroyMany', [CategoryController::class, 'destroyMany'])->name('categories.destroyMany');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::view('categories/properties/{property}/choices', 'eshop::dashboard.category.choices')->name('categories.properties.choices.index');

        Route::resource('countries', CountryController::class);
        Route::resource('countries.provinces', ProvinceController::class)->shallow()->only('store', 'update', 'destroy');
        Route::delete('countries.bulk-provinces', [BulkProvinceController::class, 'destroy'])->name('provinces.bulk-delete');
        Route::resource('shipping-methods', ShippingMethodController::class);
        Route::resource('payment-methods', PaymentMethodController::class);
        Route::get('country-shipping-methods', CountryShippingMethodController::class)->name('country-shipping-methods.index');
        Route::get('country-payment-methods', CountryPaymentMethodController::class)->name('country-payment-methods.index');

        Route::resource('manufacturers', ManufacturerController::class)->only('index');

        Route::get('notifications', NotificationController::class)->name('notifications.index');

        Route::get('users/{user}/permissions', UserPermissionController::class)->name('users.permissions.index');
        Route::resource('users', UserController::class)->only('index', 'show');

        Route::resource('slides', SlideController::class);

        Route::view('config', 'eshop::dashboard.config.index')->name('config.index');

        Route::get('analytics', AnalyticsController::class)->name('analytics.index');
        Route::get('analytics/orders', OrderAnalyticsController::class)->name('analytics.orders.index');
        if (eshop('analytics.couriers')) {
            Route::get('analytics/couriers', CourierAnalyticsController::class)->name('analytics.couriers.index');
        }
        Route::get('analytics/warehouse', WarehouseAnalyticsController::class)->name('analytics.warehouse.index');

        Route::resource('pages', PageController::class)->only('index', 'edit', 'update');

        Route::resource('pos', PosController::class)->except('index', 'show')->parameter('pos', 'cart');

        Route::post('invoices/send', [InvoiceTransmissionController::class, 'send'])->name('invoices.send');
        Route::post('invoices/cancel', [InvoiceTransmissionController::class, 'cancel'])->name('invoices.cancel');

        Route::post('invoices/search-clients', [InvoiceController::class, 'searchClients'])->name('invoices.search_clients');
        Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
        Route::resource('invoices', InvoiceController::class);
        Route::resource('clients', ClientController::class)->only('index', 'create', 'store');

        Route::resource('user-variables', UserVariableController::class)->only('index', 'store');
    });
});
