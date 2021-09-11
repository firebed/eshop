<?php

namespace Eshop;

use Eshop\Commands\InstallCommand;
use Eshop\Commands\SitemapCommand;
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
use Eshop\Models\Seo\Seo;
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
        $this->registerConfig();
        $this->registerTranslations();
        $this->registerMigrations();
        $this->registerMorphs();
        $this->registerViews();
        $this->registerRoutes();
        $this->registerCommands();
        $this->registerPublishing();

        Collection::macro('toggle', fn($item) => $this->contains($item) ? $this->except($item->id) : $this->concat([$item]));
    }

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/colors.php', 'colors'
        );
    }

    private function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'eshop');
    }

    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'eshop');

        $this->loadViewComponentsAs('eshop', [
            CategoryBreadcrumb::class,
        ]);
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/dashboard.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SitemapCommand::class,
                InstallCommand::class
            ]);
        }
    }

    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/eshop.php' => config_path('eshop.php')], 'eshop-config');

            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/eshop')], 'eshop-locale');
            $this->publishes([__DIR__ . '/../resources/lang/el.json' => resource_path('lang/el.json')], 'eshop-locale-el');

            $this->publishes([__DIR__ . '/../resources/views/customer' => resource_path('views/vendor/eshop/customer')], 'eshop-customer-views');
            $this->publishes([__DIR__ . '/../resources/views/dashboard' => resource_path('views/vendor/eshop/dashboard')], 'eshop-dashboard-views');
        }
    }

    private function registerMorphs(): void
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
            'company'         => Company::class,
            //
            'seo'             => Seo::class
        ]);
    }

}