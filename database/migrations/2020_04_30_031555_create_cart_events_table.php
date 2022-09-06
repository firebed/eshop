<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartEventsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->enum('type', ['info', 'success', 'warning', 'error'])->index();
            $table->string('action')->index();
            $table->string('title', 1000)->nullable();
            $table->json('details')->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_events');
    }
}
