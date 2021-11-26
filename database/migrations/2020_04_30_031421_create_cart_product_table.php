<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cart_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            $table->unsignedDecimal('quantity');
            $table->unsignedDecimal('price');
            $table->unsignedDecimal('compare_price')->default(0);
            $table->unsignedDecimal('discount', 3)->default(0);
            $table->unsignedDecimal('vat', 2);

            $table->boolean('pinned')->default(false);

            $table->timestamps();
            $table->softDeletes()->index();

//            $table->unique(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_product');
    }
}
