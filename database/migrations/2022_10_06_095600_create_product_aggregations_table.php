<?php

use Eshop\Models\Cart\Cart;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAggregationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('product_aggregations', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Product::class, 'parent_id')->nullable();
            $table->foreignIdFor(Product::class);
            $table->unsignedMediumInteger('total_sales')->index();
            $table->unsignedInteger('total_quantities')->index();
            $table->unsignedInteger('total_price')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_aggregations');
    }
}
