<?php

use Eshop\Models\Product\Channel;
use Eshop\Models\Product\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('channel_product', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class);
            $table->foreignIdFor(Channel::class);
            $table->unsignedDecimal('price')->nullable();
            $table->unsignedDecimal('discount', 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_product');
    }
}
