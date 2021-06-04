<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cart_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('notify')->default(FALSE);
            $table->string('color', 20)->nullable();
            $table->string('icon', 20)->nullable();
            $table->unsignedTinyInteger('group')->nullable();
            $table->enum('stock_operation', ['Capture', 'Release'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_statuses');
    }
}
