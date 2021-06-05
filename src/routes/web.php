<?php

use Eshop\Controllers\Customer\Category\CategoryController;
use Eshop\Controllers\Customer\Checkout\CheckoutCompletedController;
use Eshop\Controllers\Customer\Checkout\CheckoutDetailsController;
use Eshop\Controllers\Customer\Checkout\CheckoutLoginController;
use Eshop\Controllers\Customer\Checkout\CheckoutPaymentController;
use Eshop\Controllers\Customer\Checkout\CheckoutProductController;
use Eshop\Controllers\Customer\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    app()->setLocale(config('app.locale'));
    return view('homepage.index');
});

// Global routes
Route::group(['prefix' => config('eshop.prefix'), 'middleware' => config('eshop.middleware', ['web'])], function () {
    Route::view('/', 'customer.homepage.index')->name('home');

    Route::view('/account/profile', 'customer.account.profile.edit')->name('account.profile.edit');
    Route::view('/account/orders', 'customer.account.orders.index')->name('account.orders.index');
    Route::view('/account/orders/{order}', 'customer.account.orders.show')->name('account.orders.show');

    Route::get('categories/{category:slug}/m/{manufacturers}/{filters}', CategoryController::class)->name('customer.categories.manufacturers.filters');
    Route::get('categories/{category:slug}/m/{manufacturers}', CategoryController::class)->name('customer.categories.manufacturers');
    Route::get('categories/{category:slug}/f/{filters}', CategoryController::class)->name('customer.categories.filters');
    Route::get('categories/{category:slug}', CategoryController::class)->name('customer.categories.show');

    Route::get('products/{category:slug}/{product:slug}', [ProductController::class, 'show'])->name('customer.products.show');

    Route::as('checkout.')->group(function () {
        Route::get('cart', CheckoutProductController::class)->name('products.index');

        Route::prefix('checkout')->group(function () {

            Route::post('login', CheckoutLoginController::class)->name('login');

            Route::get('details', CheckoutDetailsController::class)->name('details.edit');

            Route::get('payment', CheckoutPaymentController::class)->name('payment.edit');

            Route::get('completed/{cart}', CheckoutCompletedController::class)->name('completed');
        });
    });
});
