<?php

use Eshop\Models\Cart\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cart::class)->constrained()->cascadeOnDelete();
            $table->string('myshipping_id')->nullable()->unique();
            $table->string('courier', 50)->nullable();
            $table->string('number')->index();
            $table->boolean('is_manual');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
}
