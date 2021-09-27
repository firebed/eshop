<?php

use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\UserAddressController;
use App\Http\Controllers\Account\UserCompanyController;
use App\Http\Controllers\Account\UserOrderController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Checkout\CheckoutCompletedController;
use App\Http\Controllers\Checkout\CheckoutDetailsController;
use App\Http\Controllers\Checkout\CheckoutLoginController;
use App\Http\Controllers\Checkout\CheckoutPaymentController;
use App\Http\Controllers\Checkout\CheckoutProductController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Pages\PageController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductOfferController;
use App\Http\Controllers\Product\ProductSearchController;
use App\Http\Controllers\Product\VariantController;
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

Route::get('/', HomepageController::class)->name('landing_page');

Route::get('mail', function () {
    Notification::route('mail', 'okan.giritli@gmail.com')->notify(new OrderShippedNotification(Cart::first()));
});

Route::get('raw-mail', function () {
    Mail::raw('Hi, welcome user!', function ($message) {
        $message->to('okan.giritli@gmail.com')->subject('Test');
    });
});

// Global routes
Route::group([
    'prefix'     => '{lang}',
    'middleware' => ['locale'],
    'where'      => ['lang' => implode('|', array_keys(config('eshop.locales', [])))]
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

        Route::get('search', [ProductSearchController::class, 'index'])->name('products.search.index');
        Route::post('search', [ProductSearchController::class, 'ajax'])->name('products.search.ajax');

        Route::get('offers', ProductOfferController::class)->name('products.offers.index');

        Route::get('{category:slug}/m/{manufacturers}/{filters}', CategoryController::class)->name('categories.manufacturers.filters');
        Route::get('{category:slug}/m/{manufacturers}', CategoryController::class)->name('categories.manufacturers');
        Route::get('{category:slug}/f/{filters}', CategoryController::class)->name('categories.filters');

        Route::get('{category:slug}/{product:slug}/{variant:slug}', [VariantController::class, 'show'])->name('variants.show');
        Route::get('{category:slug}/{product:slug}', [ProductController::class, 'show'])->name('products.show');

        Route::get('{category:slug}', CategoryController::class)->name('categories.show');
    });