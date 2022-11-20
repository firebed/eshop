<?php

use Eshop\Models\Cart\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cart::class)->constrained();
            $table->unsignedSmallInteger('courier')->nullable();
            $table->string('number')->index();
            $table->boolean('is_manual');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $temp = collect();
        $vouchers = Cart::whereNotNull('voucher')
            ->select('id', 'shipping_method_id', 'voucher', 'updated_at')
            ->with('shippingMethod')
            ->get();

        $vouchers = $vouchers->map(function ($cart) use ($temp) {
            $values = str($cart->voucher)->trim()->split("/[\s,]+/");

            for ($i = 1; $i < count($values); $i++) {
                $temp->add([
                    'cart_id'    => $cart->id,
                    'courier'    => $cart->shippingMethod?->courier(),
                    'number'     => $values->get($i),
                    'is_manual'  => true,
                    'created_at' => $cart->updated_at,
                    'updated_at' => $cart->updated_at,
                ]);
            }

            if (($value = $values->shift()) && filled($value)) {
                return [
                    'cart_id'    => $cart->id,
                    'courier'    => $cart->shippingMethod?->courier(),
                    'number'     => $value,
                    'is_manual'  => true,
                    'created_at' => $cart->updated_at,
                    'updated_at' => $cart->updated_at,
                ];
            }

            return null;
        });

        $vouchers
            ->filter(fn($v) => $v !== null && filled($v['number']) && $v['number'] !== '-')
            ->chunk(3000)
            ->each(function ($chunk) {
                DB::table('vouchers')->insert($chunk->toArray());
            });

        $temp->each(function ($chunk) {
            DB::table('vouchers')->insert($chunk);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
}
