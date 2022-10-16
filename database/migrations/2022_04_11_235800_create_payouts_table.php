<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', static function (Blueprint $table) {
            $table->id();
            $table->morphs('originator');
            $table->string('reference_id')->index();
            $table->unsignedSmallInteger('orders');
            $table->unsignedDecimal('fees')->default(0);
            $table->unsignedDecimal('total');
            $table->string('attachment')->nullable();
            
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
}
