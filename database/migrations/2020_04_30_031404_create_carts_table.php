<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_method_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('cart_statuses')->nullOnDelete();
            $table->string('cookie_id', 36)->unique()->nullable();
            $table->string('payment_id')->unique()->nullable();
            $table->decimal('payment_fee')->default(0);
            $table->decimal('shipping_fee')->default(0);
            $table->unsignedInteger('parcel_weight')->default(0);
            $table->enum('document_type', ['Invoice', 'Receipt'])->nullable();
            $table->unsignedDecimal('total', 10)->default(0);
            $table->text('details')->nullable();
            $table->string('voucher')->nullable()->index();
            $table->string('email')->nullable();
            $table->string('ip')->nullable();
            $table->enum('channel', ['eshop', 'phone', 'pos', 'facebook', 'instagram', 'other'])->default('eshop');

            $table->timestamp('submitted_at')->nullable()->index();
            $table->timestamp('viewed_at')->nullable();
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
        Schema::dropIfExists('carts');
    }
}
