<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('category_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_property_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->unsignedInteger('position');
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
        Schema::dropIfExists('category_choices');
    }
}
