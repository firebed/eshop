<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('category_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('index', ['None', 'Simple', 'Multiple'])->default('None');
            $table->enum('value_restriction', ['None', 'Simple', 'Multiple'])->default('None');
            $table->boolean('visible')->default(TRUE);
            $table->boolean('promote')->default(FALSE);
            $table->boolean('show_caption')->default(FALSE);
            $table->boolean('show_empty_value')->default(FALSE);
            $table->unsignedTinyInteger('position')->default(0);
            $table->string('slug');
            $table->unique(['category_id', 'slug']);
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
        Schema::dropIfExists('category_properties');
    }
}
