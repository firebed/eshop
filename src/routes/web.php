<?php

use Eshop\Controllers\Customer\Category\CategoryController;
use Eshop\Controllers\Customer\Checkout\CheckoutCompletedController;
use Eshop\Controllers\Customer\Checkout\CheckoutDetailsController;
use Eshop\Controllers\Customer\Checkout\CheckoutLoginController;
use Eshop\Controllers\Customer\Checkout\CheckoutPaymentController;
use Eshop\Controllers\Customer\Checkout\CheckoutProductController;
use Eshop\Controllers\Customer\Product\ProductController;
use Eshop\Controllers\Customer\Product\ProductVariantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    app()->setLocale(config('app.locale'));
    return view('eshop::customer.homepage.index');
});

// Global routes
Route::group([
    'prefix'     => config('eshop.prefix'),
    'middleware' => config('eshop.middleware', ['web']),
    'where'      => ['lang' => 'el|en']
],
    function () {
        Route::view('/', 'eshop::customer.homepage.index')->name('home');

        Route::view('/account/profile', 'eshop::customer.account.profile.edit')->name('account.profile.edit');
        Route::view('/account/orders', 'eshop::customer.account.orders.index')->name('account.orders.index');
        Route::view('/account/orders/{order}', 'eshop::customer.account.orders.show')->name('account.orders.show');

        Route::as('checkout.')->group(function () {
            Route::get('cart', CheckoutProductController::class)->name('products.index');

            Route::prefix('checkout')->group(function () {
                Route::post('login', CheckoutLoginController::class)->name('login');

                Route::get('details', CheckoutDetailsController::class)->name('details.edit');

                Route::get('payment', CheckoutPaymentController::class)->name('payment.edit');

                Route::get('completed/{cart}', CheckoutCompletedController::class)->name('completed');
            });
        });

        Route::get('{category:slug}/m/{manufacturers}/{filters}', CategoryController::class)->name('customer.categories.manufacturers.filters');
        Route::get('{category:slug}/m/{manufacturers}', CategoryController::class)->name('customer.categories.manufacturers');
        Route::get('{category:slug}/f/{filters}', CategoryController::class)->name('customer.categories.filters');
        Route::get('{category:slug}', CategoryController::class)->name('customer.categories.show');

        Route::get('{category:slug}/{product:slug}/{variant:slug}', [ProductVariantController::class, 'show'])->name('customer.variants.show');
        Route::get('{category:slug}/{product:slug}', [ProductController::class, 'show'])->name('customer.products.show');
    });
