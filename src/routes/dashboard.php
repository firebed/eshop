<?php

use App\Http\Controllers\Dashboard\Cart\CartController;
use App\Http\Controllers\Dashboard\Cart\PrintController;
use App\Http\Controllers\Dashboard\Intl\CountryController;
use App\Http\Controllers\Dashboard\Intl\PaymentMethodController;
use App\Http\Controllers\Dashboard\Intl\ShippingMethodController;
use App\Http\Controllers\Dashboard\Product\CategoryController;
use App\Http\Controllers\Dashboard\Product\ProductController;
use App\Http\Controllers\Dashboard\Product\ProductImageController;
use App\Http\Controllers\Dashboard\Product\ProductVariantController;
use App\Http\Controllers\Dashboard\UserController;
use App\Mail\OrderShippedMail;
use App\Models\Cart\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
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

    Route::prefix('api')->group(function () {
        Route::get('slugify', function (Request $request) {
            return slugify($request->input('fields'));
        });
    });

    Route::get('/mailable/cart/{cart}', function (Cart $cart) {
        return (new OrderShippedMail($cart));
    });
});
