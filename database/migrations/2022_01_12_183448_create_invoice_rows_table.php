<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('invoice_rows', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            
            $table->string('code');
            $table->unsignedTinyInteger('unit');
            $table->string('description');
            $table->unsignedDecimal('quantity');
            $table->unsignedDecimal('price', 10, 4);
            $table->unsignedDecimal('discount', 3);
            $table->unsignedDecimal('vat_percent', 2);
            $table->unsignedInteger('position')->default(0);
            
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
        Schema::dropIfExists('invoice_rows');
    }
}
