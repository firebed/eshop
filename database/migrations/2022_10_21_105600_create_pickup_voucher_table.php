<?php

use Eshop\Models\Cart\Pickup;
use Eshop\Models\Cart\Voucher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupVoucherTable extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_voucher', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pickup::class);
            $table->foreignIdFor(Voucher::class);
            $table->timestamps();
        });
        
        $pickup = Pickup::create(['pickup_id' => 'migrate']);
        //\Illuminate\Support\Facades\DB::table('pickup_voucher')->insert()
        $pickup->vouchers()->sync(Voucher::pluck('id'));
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_voucher');
    }
}
