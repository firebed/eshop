<?php

namespace Ecommerce;

use Ecommerce\Livewire\Customer\Checkout\CartButton;
use Ecommerce\Models\Cart\Cart;
use Ecommerce\Models\Invoice\Company;
use Ecommerce\Models\Invoice\Invoice;
use Ecommerce\Models\Location\Country;
use Ecommerce\Models\Location\CountryPaymentMethod;
use Ecommerce\Models\Location\CountryShippingMethod;
use Ecommerce\Models\Product\Category;
use Ecommerce\Models\Product\CategoryChoice;
use Ecommerce\Models\Product\CategoryProperty;
use Ecommerce\Models\Product\Manufacturer;
use Ecommerce\Models\Product\Product;
use Ecommerce\Models\Product\VariantType;
use Ecommerce\Models\Settings;
use Ecommerce\Models\User;
use Ecommerce\Repository\CartRepository;
use Ecommerce\Repository\Contracts\CartContract;
use Ecommerce\Repository\Contracts\Order;
use Ecommerce\Repository\Contracts\ProductContract;
use Ecommerce\Repository\ProductRepository;
use Ecommerce\View\Components\CategoryBreadcrumb;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class EcommerceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(ProductContract::class, ProductRepository::class);

        $this->app->singleton(CartContract::class, CartRepository::class);

        $this->app->singleton(Order::class, function () {
            if (Auth::check()) {
                return auth()->user()->activeCart()->firstOrNew();
            }

//            if (session()->has('cart-session-id')) {
//                $sessionId = session('cart-session-id');
//                $cart = Cart::find($sessionId);
//                if ($cart !== NULL && !$cart->isSubmitted()) {
//                    return $cart;
//                }
//                session()->forget('cart-session-id');
//            }

            $request = request();
            if ($request && $request->hasCookie('cart-cookie-id')) {
                $cookieId = $request->cookie('cart-cookie-id');
                $cart = Cart::firstWhere('cookie_id', $cookieId);
                if ($cart !== NULL && !$cart->isSubmitted()) {
//                    session()->put('cart-session-id', $cart->id);
                    return $cart;
                }
                cookie()->queue(cookie()->forget('cart-cookie-id'));
            }

            return new Cart();
        });

        $this->app->singleton('countries', fn() => Country::orderBy('name')->get());
        $this->app->singleton('settings', fn() => Settings::first());

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'com');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/dashboard.php');

        $this->loadViewComponentsAs('com', [
            CategoryBreadcrumb::class,
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'com');

        $this->publishes([__DIR__ . '/config/ecommerce' => config_path('ecommerce.php')], 'config');
        $this->publishes([__DIR__ . '/resources/lang' => resource_path('lang/vendor/ecommerce')], 'lang');
        $this->publishes([__DIR__ . '/resources/views/customer' => resource_path('views/vendor/ecommerce')], 'customer-views');
        $this->publishes([__DIR__ . '/resources/views/dashboard' => resource_path('views/vendor/ecommerce')], 'dashboard-views');
        $this->publishes([__DIR__ . '/public' => public_path('vendor/ecommerce')], 'public');

        $this->assignMorphs();

        Collection::macro('toggle', fn($item) => $this->contains($item) ? $this->except($item->id) : $this->concat([$item]));

        Livewire::component('customer.checkout.cart-button', CartButton::class);
    }

    private function assignMorphs(): void
    {
        Relation::morphMap([
            'user' => User::class,

            'category'          => Category::class,
            'category_choice'   => CategoryChoice::class,
            'category_property' => CategoryProperty::class,

            'product'         => Product::class,
            'variant_type'    => VariantType::class,
            //
            'payment_method'  => CountryPaymentMethod::class,
            'shipping_method' => CountryShippingMethod::class,
            //
            'manufacturer'    => Manufacturer::class,
            //
            'cart'            => Cart::class,
            'invoice'         => Invoice::class,
            'company'         => Company::class
        ]);
    }

}