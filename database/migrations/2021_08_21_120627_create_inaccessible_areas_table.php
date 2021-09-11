<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInaccessibleAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('inaccessible_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_method_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('region')->nullable();
            $table->string('type')->nullable();
            $table->string('courier_store')->nullable();
            $table->string('courier_county')->nullable();
            $table->string('courier_address')->nullable();
            $table->string('courier_phone')->nullable();
            $table->string('postcode')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('inaccessible_areas');
    }
}