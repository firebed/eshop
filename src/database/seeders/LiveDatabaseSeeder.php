<?php

namespace Database\Seeders;

use Database\Seeders\Live\CartsTableSeeder;
use Database\Seeders\Live\CategoriesTableSeeder;
use Database\Seeders\Live\CountriesTableSeeder;
use Database\Seeders\Live\LocalesTableSeeder;
use Database\Seeders\Live\ProductsTableSeeder;
use Database\Seeders\Live\TopSellersSeeder;
use Database\Seeders\Live\UnitsTableSeeder;
use Database\Seeders\Live\UsersTableSeeder;
use Database\Seeders\Live\VatsTableSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LiveDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->unsignedInteger('old_id');
        });

        Schema::table('products', function(Blueprint $table) {
            $table->unsignedInteger('old_id');
        });

        $this->call(LocalesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);

        $this->call(UsersTableSeeder::class);

        $this->call(VatsTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);

        $this->call(CartsTableSeeder::class);
//        $this->call(CitiesTableSeeder::class);

        $this->call(TopSellersSeeder::class);

        Schema::dropColumns('users', ['old_id']);
        Schema::dropColumns('products', ['old_id']);
    }
}
