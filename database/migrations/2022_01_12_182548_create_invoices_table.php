<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('invoices', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('payment_method');
            $table->string('relative_document')->nullable();
            $table->string('transaction_purpose')->nullable();
            $table->string('row')->nullable();
            $table->unsignedBigInteger('number')->nullable();

            $table->unsignedDecimal('total_net_value');
            $table->unsignedDecimal('total_vat_amount');
            $table->unsignedDecimal('total');

            $table->text('details')->nullable();

            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            $table->index(['row', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
}
