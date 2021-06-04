<?php

namespace Firebed;

use App\Models\Cart\Cart;
use App\Models\Invoice\Company;
use App\Models\Invoice\Invoice;
use App\Models\Location\Country;
use App\Models\Location\CountryPaymentMethod;
use App\Models\Location\CountryShippingMethod;
use App\Models\Product\Category;
use App\Models\Product\CategoryChoice;
use App\Models\Product\CategoryProperty;
use App\Models\Product\Manufacturer;
use App\Models\Product\Product;
use App\Models\Product\VariantType;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class EcommerceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton('countries', fn() => Country::orderBy('name')->get());
        $this->app->singleton('settings', fn() => Settings::first());

        $this->loadViewsFrom(__DIR__ . '/views', 'com');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/dashboard.php');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'com');

        $this->publishes([__DIR__ . '/resources/lang' => resource_path('lang/vendor/com')], 'lang');
        $this->publishes([__DIR__ . '/resources/views' => resource_path('views/vendor/com')], 'views');
        $this->publishes([__DIR__ . '/public' => public_path('vendor/com')], 'public');

        $this->assignMorphs();

        Collection::macro('toggle', fn($item) => $this->contains($item) ? $this->except($item->id) : $this->concat([$item]));
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