<?php

use Ecommerce\Controllers\Dashboard\Cart\CartController;
use Ecommerce\Controllers\Dashboard\Cart\PrintController;
use Ecommerce\Controllers\Dashboard\Intl\CountryController;
use Ecommerce\Controllers\Dashboard\Intl\PaymentMethodController;
use Ecommerce\Controllers\Dashboard\Intl\ShippingMethodController;
use Ecommerce\Controllers\Dashboard\Product\CategoryController;
use Ecommerce\Controllers\Dashboard\Product\ProductController;
use Ecommerce\Controllers\Dashboard\Product\ProductImageController;
use Ecommerce\Controllers\Dashboard\Product\ProductVariantController;
use Ecommerce\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('products/create-group', [ProductController::class, 'createGroup'])->name('products.create-group');

        Route::resource('products', ProductController::class)->only('index', 'create', 'edit');
        Route::resource('products.variants', ProductVariantController::class)->shallow()->only('index');
        Route::get('products/{product}/images', [ProductImageController::class, 'index'])->name('products.images.index');

        Route::get('carts/{cart}/print', PrintController::class)->name('carts.print');
        Route::resource('carts', CartController::class);

        Route::resource('categories', CategoryController::class)->only('index', 'show');
        Route::view('categories/properties/{property}/choices', 'dashboard.category.choices')->name('categories.properties.choices.index');

        Route::resource('countries', CountryController::class)->only('index');
        Route::resource('shipping-methods', ShippingMethodController::class)->only('index');
        Route::resource('payment-methods', PaymentMethodController::class)->only('index');

        Route::resource('users', UserController::class)->only('index', 'show');
    });
});
