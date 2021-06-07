<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('manufacturer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('has_variants')->default(false)->index();
            $table->unsignedDecimal('vat', 2)->default(0);
            $table->unsignedInteger('weight')->default(0);
            $table->unsignedDecimal('price')->index()->default(0);
            $table->unsignedDecimal('compare_price')->default(0);
            $table->unsignedDecimal('discount', 3)->default(0)->index();
            $table->unsignedDecimal('net_value')->storedAs('ROUND(price * (1 - discount), 2)')->index();
            $table->decimal('stock')->default(0);

            $table->boolean('visible')->default(true)->index();

            $table->boolean('display_stock')->default(true);
            $table->integer('display_stock_lt')->nullable();

            $table->boolean('available')->default(true);
            $table->integer('available_gt')->nullable();


            $table->string('location', 50)->nullable();
            $table->string('sku', 100)->nullable()->index();
            $table->string('barcode', 50)->nullable()->unique();
//            $table->string('slug')->unique();
            $table->string('slug');

            $table->timestamps();
            $table->softDeletes()->index();

            $table->foreign('vat')->references('regime')->on('vats')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('products', function ($table) {
            $table->dropIndex('search');
        });

        Schema::dropIfExists('products');
    }
}
