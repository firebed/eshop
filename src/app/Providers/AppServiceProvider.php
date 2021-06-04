<?php

namespace App\Providers;

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
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton('countries', fn() => Country::orderBy('name')->get());
        $this->app->singleton('settings', fn() => Settings::first());

        Paginator::useBootstrap();
        $this->assignMorphs();
        $this->logQueries();

        // Toggles the item in or out from the collection
        Collection::macro('toggle', fn($item) => $this->contains($item) ? $this->except($item->id) : $this->concat([$item]));
    }

    private function logQueries(): void
    {
        File::delete(storage_path('/logs/query.log'));
        DB::listen(function ($query) {
            File::append(
                storage_path('/logs/query.log'),
                $query->sql . ' [' . implode(', ', $query->bindings) . ']' . " ($query->time)" . PHP_EOL
            );
        });
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
