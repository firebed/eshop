<?php

use Eshop\Controllers\Customer\Category\CategoryController;
use Eshop\Controllers\Customer\Checkout\CheckoutCompletedController;
use Eshop\Controllers\Customer\Checkout\CheckoutDetailsController;
use Eshop\Controllers\Customer\Checkout\CheckoutLoginController;
use Eshop\Controllers\Customer\Checkout\CheckoutPaymentController;
use Eshop\Controllers\Customer\Checkout\CheckoutProductController;
use Eshop\Controllers\Customer\Product\ProductController;
use Eshop\Controllers\Customer\Product\ProductVariantController;
use Eshop\Controllers\Dashboard\Account\PasswordController;
use Eshop\Controllers\Dashboard\Account\ProfileController;
use Eshop\Controllers\Dashboard\Account\UserAddressController;
use Eshop\Controllers\Dashboard\Account\UserCompanyController;
use Eshop\Controllers\Dashboard\Account\UserOrdersController;
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

        Route::group(['middleware' => 'auth', 'prefix' => 'account', 'as' => 'account.'], function () {
            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

            Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('password', [PasswordController::class, 'update'])->name('password.update');

            Route::resource('orders', UserOrdersController::class)->only('index', 'show');

            Route::resource('addresses', UserAddressController::class)->except('show');

            Route::resource('companies', UserCompanyController::class)->except('show');

//            Route::view('invoices', 'eshop::customer.invoices.index')->name('invoices.index');
        });

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
