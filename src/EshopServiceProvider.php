<?php

namespace Eshop;

use Eshop\Commands\AcsPaymentsCommand;
use Eshop\Commands\CartAbandonmentCommand;
use Eshop\Commands\CartCleanup;
use Eshop\Commands\InstallCommand;
use Eshop\Commands\ScoutIndexCommand;
use Eshop\Commands\SitemapCommand;
use Eshop\Commands\SkroutzFeedCommand;
use Eshop\Commands\SpeedExPaymentsCommand;
use Eshop\Middleware\Admin;
use Eshop\Middleware\Locale;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartInvoice;
use Eshop\Models\Location\CountryPaymentMethod;
use Eshop\Models\Location\CountryShippingMethod;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\CategoryChoice;
use Eshop\Models\Product\CategoryProperty;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\ProductVariantOption;
use Eshop\Models\Product\VariantType;
use Eshop\Models\Seo\Seo;
use Eshop\Models\Slide\Slide;
use Eshop\Models\User\Company;
use Eshop\Models\User\User;
use Eshop\Providers\AuthServiceProvider;
use Eshop\Providers\CartServiceProvider;
use Eshop\Providers\EventServiceProvider;
use Eshop\Providers\FortifyServiceProvider;
use Eshop\Providers\LivewireServiceProvider;
use Eshop\Services\Skroutz\Actions\CreateSkroutzXML;
use Eshop\View\Components\CategoryBreadcrumb;
use Eshop\View\Components\LabelPrinterDialog;
use Eshop\View\Components\NewArrivals;
use Eshop\View\Components\Sales;
use Eshop\View\Components\TrendingProducts;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageServiceProvider;

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

        $this->app->bindIf('skroutz', CreateSkroutzXML::class);
        
        app('router')->aliasMiddleware('locale', Locale::class);
        app('router')->aliasMiddleware('admin', Admin::class);

        Collection::macro('toggle', fn($item) => $this->contains($item) ? $this->except($item->id) : $this->concat([$item]));
    }

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(CartServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
        $this->app->register(FortifyServiceProvider::class);
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/colors.php', 'colors');
        $this->mergeConfigFrom(__DIR__ . '/../config/eshop.php', 'eshop');
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
            TrendingProducts::class,
        ]);

        Blade::component(Sales::class, 'sales');
        Blade::component(NewArrivals::class, 'new-arrivals');
        Blade::component(TrendingProducts::class, 'trending-products');
        Blade::component(LabelPrinterDialog::class, 'label-printer-dialog');
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../stubs/routes/routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/dashboard.php');
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SitemapCommand::class,
                InstallCommand::class,
                ScoutIndexCommand::class,
                SkroutzFeedCommand::class,
                AcsPaymentsCommand::class,
                SpeedExPaymentsCommand::class,
                CartCleanup::class,
                CartAbandonmentCommand::class
            ]);
        }
    }

    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/eshop.php' => config_path('eshop.php')], 'eshop-config');
            $this->publishes([__DIR__ . '/../resources/lang/el.json' => resource_path('lang/el.json')], 'eshop-lang-el');
            $this->publishes([__DIR__ . '/../stubs/controllers' => app_path('Http\Controllers')], 'eshop-customer-controllers');
            $this->publishes([__DIR__ . '/../stubs/resources/views' => resource_path('views')], 'eshop-customer-views');

            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/eshop')], 'eshop-lang');
            $this->publishes([__DIR__ . '/../resources/views/dashboard' => resource_path('views/vendor/eshop/dashboard')], 'eshop-dashboard-views');

            $this->publishes([
                __DIR__ . '/../stubs/config/eshop.php' => config_path('eshop.php'),
                __DIR__ . '/../resources/lang/el.json' => resource_path('lang/el.json'),
                __DIR__ . '/../stubs/controllers'      => app_path('Http\Controllers'),
                __DIR__ . '/../stubs/resources/views'  => resource_path('views'),
                __DIR__ . '/../stubs/livewire'         => app_path('Http\Livewire')
            ], 'eshop-setup');
        }
    }

    private function registerMorphs(): void
    {
        Relation::morphMap([
            'user'                    => User::class,
            //
            'category'                => Category::class,
            'category_choice'         => CategoryChoice::class,
            'category_property'       => CategoryProperty::class,
            //
            'product'                 => Product::class,
            'variant_type'            => VariantType::class,
            'product_variant_type'    => ProductVariantOption::class,
            //
            'country_payment_method'  => CountryPaymentMethod::class,
            'country_shipping_method' => CountryShippingMethod::class,
            //
            'manufacturer'            => Manufacturer::class,
            //
            'cart'                    => Cart::class,
            'cart_invoice'            => CartInvoice::class,
            'company'                 => Company::class,
            //
            'seo'                     => Seo::class,
            //
            'slide'                   => Slide::class
        ]);
    }

}