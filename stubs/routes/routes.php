<?php

use Eshop\Controllers\Customer\Account\PasswordController;
use Eshop\Controllers\Customer\Account\ProfileController;
use Eshop\Controllers\Customer\Account\UserAddressController;
use Eshop\Controllers\Customer\Account\UserCompanyController;
use Eshop\Controllers\Customer\Account\UserOrderController;
use Eshop\Controllers\Customer\Category\CategoryController;
use Eshop\Controllers\Customer\Checkout\CheckoutCompletedController;
use Eshop\Controllers\Customer\Checkout\CheckoutDetailsController;
use Eshop\Controllers\Customer\Checkout\CheckoutLoginController;
use Eshop\Controllers\Customer\Checkout\CheckoutPaymentController;
use Eshop\Controllers\Customer\Checkout\CheckoutProductController;
use Eshop\Controllers\Customer\Checkout\OrderTrackingController;
use Eshop\Controllers\Customer\HomepageController;
use Eshop\Controllers\Customer\Pages\PageController;
use Eshop\Controllers\Customer\Product\ProductCollectionController;
use Eshop\Controllers\Customer\Product\ProductController;
use Eshop\Controllers\Customer\Product\ProductNewArrivalsController;
use Eshop\Controllers\Customer\Product\ProductOfferController;
use Eshop\Controllers\Customer\Product\ProductSearchController;
use Eshop\Models\Cart\Cart;
use Eshop\Notifications\OrderShippedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (app()->isLocal()) {
    Route::get('mail', function () {
        Notification::route('mail', 'okan.giritli@gmail.com')->notify(new OrderShippedNotification(Cart::first()));
    });

    Route::get('raw-mail', function () {
        Mail::raw('Hi, welcome user!', function ($message) {
            $message->to('okan.giritli@gmail.com')->subject('Test');
        });
    });
}

Route::get('/', HomepageController::class)->name('landing_page');

// Global routes
Route::group([
    'prefix'     => '{lang}',
    'middleware' => ['locale'],
    'where'      => ['lang' => implode('|', array_keys(eshop('locales', [])))]
],
    function () {
        Route::get('/', HomepageController::class)->name('home');

        Route::group(['middleware' => 'auth', 'prefix' => 'account', 'as' => 'account.'], function () {
            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

            Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('password', [PasswordController::class, 'update'])->name('password.update');

            Route::resource('orders', UserOrderController::class)->only('index', 'show');

            Route::resource('addresses', UserAddressController::class)->except('show');

            Route::resource('companies', UserCompanyController::class)->except('show');

//            Route::view('invoices', 'eshop::customer.invoices.index')->name('invoices.index');
        });

        Route::as('checkout.')->group(function () {
            Route::get('cart', CheckoutProductController::class)->name('products.index');

            Route::prefix('checkout')->group(function () {
                Route::post('login', CheckoutLoginController::class)->name('login');

                Route::get('details', [CheckoutDetailsController::class, 'edit'])->name('details.edit');
                Route::put('details', [CheckoutDetailsController::class, 'update'])->name('details.update');
                Route::post('details/userShipping', [CheckoutDetailsController::class, 'userShipping'])->name('details.userShipping');
                Route::post('details/shippingCountry', [CheckoutDetailsController::class, 'shippingCountry'])->name('details.shippingCountry');

                Route::get('payment', [CheckoutPaymentController::class, 'edit'])->name('payment.edit');
                Route::put('payment', [CheckoutPaymentController::class, 'update'])->name('payment.update');
                Route::post('payment', [CheckoutPaymentController::class, 'store'])->name('payment.store');

                Route::get('completed/{cart}', CheckoutCompletedController::class)->name('completed');
            });
        });

        Route::get('{page}', PageController::class)->where('page', '(shipping-methods|payment-methods|terms-of-service|data-protection|return-policy|cancellation-policy|secure-transactions)')->name('pages.show');

        Route::post('order-tracking/voucher', [OrderTrackingController::class, 'searchByVoucher'])->name('order-tracking.search_by_voucher');
        Route::post('order-tracking/id', [OrderTrackingController::class, 'searchById'])->name('order-tracking.search_by_id');
        Route::get('order-tracking', [OrderTrackingController::class, 'index'])->name('order-tracking.index');
        Route::get('order-tracking/{order}', [OrderTrackingController::class, 'show'])->name('order-tracking.show');

        Route::get('search', [ProductSearchController::class, 'index'])->name('products.search.index');
        Route::post('search', [ProductSearchController::class, 'ajax'])->name('products.search.ajax');

        Route::get('new-arrivals', ProductNewArrivalsController::class)->name('products.new-arrivals.index');
        Route::get('offers', ProductOfferController::class)->name('products.offers.index');
        Route::get('collections/{collection:slug}', ProductCollectionController::class)->name('products.collections.index');

        Route::get('{category:slug}/m/{manufacturers}/{filters}', CategoryController::class)->name('categories.manufacturers.filters');
        Route::get('{category:slug}/m/{manufacturers}', CategoryController::class)->name('categories.manufacturers');
        Route::get('{category:slug}/f/{filters}', CategoryController::class)->name('categories.filters');

        Route::get('{category:slug}/{product:slug}', [ProductController::class, 'show'])->name('products.show');

        Route::get('{category:slug}', CategoryController::class)->name('categories.show');
    });
