<?php

use Eshop\Controllers\Dashboard\Analytics\AnalyticsController;
use Eshop\Controllers\Dashboard\Cart\CartController;
use Eshop\Controllers\Dashboard\Cart\PrintController;
use Eshop\Controllers\Dashboard\Intl\CountryController;
use Eshop\Controllers\Dashboard\Intl\PaymentMethodController;
use Eshop\Controllers\Dashboard\Intl\ShippingMethodController;
use Eshop\Controllers\Dashboard\Product\CategoryController;
use Eshop\Controllers\Dashboard\Product\ManufacturerController;
use Eshop\Controllers\Dashboard\Product\ProductController;
use Eshop\Controllers\Dashboard\Product\ProductImageController;
use Eshop\Controllers\Dashboard\Product\ProductTrashController;
use Eshop\Controllers\Dashboard\Product\VariantBulkController;
use Eshop\Controllers\Dashboard\Product\VariantBulkImageController;
use Eshop\Controllers\Dashboard\Product\VariantController;
use Eshop\Controllers\Dashboard\User\UserController;
use Eshop\Controllers\Dashboard\User\UserPermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('products/{product}/images', [ProductImageController::class, 'index'])->name('products.images.index');
        Route::get('products/trashed', ProductTrashController::class)->name('products.trashed.index');
        Route::resource('products', ProductController::class)->except('show');

        Route::put('variants/images', VariantBulkImageController::class)->name('variants.bulk-images.update');

        Route::prefix('products/{product}/variants')->as('variants.')->group(function () {
            Route::get('bulk-create', [VariantBulkController::class, 'create'])->name('bulk-create');
            Route::post('bulk-create', [VariantBulkController::class, 'store'])->name('bulk-store');
            Route::get('bulk-edit', [VariantBulkController::class, 'edit'])->name('bulk-edit');
            Route::put('bulk-edit', [VariantBulkController::class, 'update'])->name('bulk-update');
            Route::delete('bulk-destroy', [VariantBulkController::class, 'destroy'])->name('bulk-destroy');
        });

        Route::resource('products.variants', VariantController::class)->shallow()->except('show');

        Route::get('carts/{cart}/print', PrintController::class)->name('carts.print');
        Route::resource('carts', CartController::class);

        Route::resource('categories', CategoryController::class)->only('index', 'show');
        Route::view('categories/properties/{property}/choices', 'eshop::dashboard.category.choices')->name('categories.properties.choices.index');

        Route::resource('countries', CountryController::class)->only('index');
        Route::resource('shipping-methods', ShippingMethodController::class)->only('index');
        Route::resource('payment-methods', PaymentMethodController::class)->only('index');

        Route::resource('manufacturers', ManufacturerController::class)->only('index');

        Route::get('users/{user}/permissions', UserPermissionController::class)->name('users.permissions.index');
        Route::resource('users', UserController::class)->only('index', 'show');

        Route::view('config', 'eshop::dashboard.config.index')->name('config.index');

        Route::get('analytics', AnalyticsController::class)->name('analytics');
    });
});
