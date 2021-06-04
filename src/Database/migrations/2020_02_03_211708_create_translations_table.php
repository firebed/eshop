<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->morphs('translatable');
            $table->string('locale', 2);
            $table->text('translation');
            $table->string('cluster', 50)->nullable();

            $table->unique(['locale', 'translatable_id', 'translatable_type', 'cluster'], 'unique_translation');
            $table->foreign('locale')->references('name')->on('locales')->onUpdate('CASCADE')->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE translations ADD FULLTEXT search(translation)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
}
