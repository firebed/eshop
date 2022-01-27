<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('clients', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vat_number');
            $table->string('tax_authority')->nullable();
            $table->string('job')->nullable();
            $table->string('country', 2);
            $table->string('city');
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('postcode');
            $table->string('phone_number');
            $table->timestamps();
            
            $table->index(['name', 'vat_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
}
