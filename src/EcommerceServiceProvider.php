<?php

namespace Firebed;

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
        $this->loadViewsFrom(__DIR__ . '/views', 'com');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/dashboard.php');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'com');

        $this->publishes([__DIR__ . '/resources/lang' => resource_path('lang/vendor/com')], 'lang');
        $this->publishes([__DIR__ . '/resources/views' => resource_path('views/vendor/com')], 'views');
        $this->publishes([__DIR__ . '/public' => public_path('vendor/com')], 'public');
    }
}