<?php

namespace Eshop;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Invoice\Company;
use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Location\CountryPaymentMethod;
use Eshop\Models\Location\CountryShippingMethod;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\CategoryChoice;
use Eshop\Models\Product\CategoryProperty;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Eshop\Models\User;
use Eshop\View\Components\CategoryBreadcrumb;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class EshopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadConfig();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->loadViews();
        $this->loadRoutes();
        $this->loadAssets();

        Collection::macro('toggle', fn($item) => $this->contains($item) ? $this->except($item->id) : $this->concat([$item]));
    }

    private function loadConfig(): void
    {
        $this->publishes([__DIR__ . '/config/eshop.php' => config_path('eshop.php')], 'eshop-config');
    }

    private function loadAssets(): void
    {
        $this->publishes([__DIR__ . '/public' => public_path('vendor/eshop')], 'eshop-assets');
    }

    private function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'eshop');

        $this->publishes([__DIR__ . '/resources/lang' => resource_path('lang/vendor/eshop')], 'eshop-locale');
    }

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->assignMorphs();
    }

    private function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'eshop');

        $this->loadViewComponentsAs('com', [
            CategoryBreadcrumb::class,
        ]);

        $this->publishes([__DIR__ . '/resources/views/customer' => resource_path('views/vendor/eshop/customer')], 'eshop-customer-views');
        $this->publishes([__DIR__ . '/resources/views/dashboard' => resource_path('views/vendor/eshop/dashboard')], 'eshop-dashboard-views');

    }

    private function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/dashboard.php');
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