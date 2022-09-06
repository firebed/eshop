<?php

namespace Eshop\Commands;

use Eshop\Actions\ReportError;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Location\Address;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class CartCleanup extends Command
{
    protected $signature = 'cart:cleanup';

    protected $description = 'Deletes all old abandoned carts.';

    public function handle(ReportError $report): void
    {
        DB::beginTransaction();
        try {
            $before = eshop('cart.abandoned.delete_before', '90 days');

            $ids = Cart::query()
                ->whereNull('submitted_at')
                ->whereNull('email')
                ->whereNull('user_id')
                ->whereDate('updated_at', '<', today()->sub($before))
                ->pluck('id');

            Cart::whereIntegerInRaw('id', $ids)->delete();

            Address::whereIntegerInRaw('addressable_id', $ids)
                ->where('addressable_type', 'cart')
                ->delete();

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();

            $this->error($t->getMessage());
            $report->handle($t->getMessage(), $t->getTraceAsString());
        }
    }
}