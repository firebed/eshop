<?php

use Eshop\Models\Cart\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('notifications', static function (Blueprint $table) {
            $table->id();
            $table->string('text', 1000);
            $table->json('metadata')->nullable();
            $table->string('body')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}
