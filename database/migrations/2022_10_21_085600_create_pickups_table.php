<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupsTable extends Migration
{
    public function up(): void
    {
        Schema::create('pickups', static function (Blueprint $table) {
            $table->id();
            $table->string('pickup_id')->index();
            $table->timestamps();
            $table->timestamp('cancelled_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickups');
    }
}
