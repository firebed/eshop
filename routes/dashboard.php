<?php

use Eshop\Controllers\Dashboard\Cart\CartController;
use Eshop\Controllers\Dashboard\Cart\PrintController;
use Eshop\Controllers\Dashboard\Intl\CountryController;
use Eshop\Controllers\Dashboard\Intl\PaymentMethodController;
use Eshop\Controllers\Dashboard\Intl\ShippingMethodController;
use Eshop\Controllers\Dashboard\Product\CategoryController;
use Eshop\Controllers\Dashboard\Product\ManufacturerController;
use Eshop\Controllers\Dashboard\Product\ProductController;
use Eshop\Controllers\Dashboard\Product\ProductImageController;
use Eshop\Controllers\Dashboard\Product\ProductVariantController;
use Eshop\Controllers\Dashboard\User\UserController;
use Eshop\Controllers\Dashboard\User\UserPermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('products/create-group', [ProductController::class, 'createGroup'])->name('products.create-group');

        Route::get('products/{product}/images', [ProductImageController::class, 'index'])->name('products.images.index');
        Route::resource('products', ProductController::class)->only('index', 'create', 'edit');
        Route::resource('products.variants', ProductVariantController::class)->shallow()->only('index');

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
    });
});
