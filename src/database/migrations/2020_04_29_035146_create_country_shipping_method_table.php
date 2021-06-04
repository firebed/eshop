<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryShippingMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('country_shipping_method', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_method_id')->constrained()->cascadeOnDelete();
            $table->decimal('fee')->default(0);
            $table->decimal('cart_total')->default(0);
            $table->integer('weight_limit')->default(0);
            $table->decimal('weight_excess_fee')->default(0);
            $table->decimal('inaccessible_area_fee')->default(0);
            $table->unsignedTinyInteger('position')->default(0);
            $table->boolean('visible')->default(TRUE);
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
        Schema::dropIfExists('country_shipping_method');
    }
}
