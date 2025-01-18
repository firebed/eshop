<?php

use Eshop\Models\Cart\Cart;
use Eshop\Models\Product\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', static function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->string('title');
            $table->string('disk');
            $table->string('src');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
}
