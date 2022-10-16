<?php

namespace Eshop\Services\Payout;

use Illuminate\Console\Command;

abstract class PayoutsCommand extends Command
{
    /**
     * The add method will only add the item to the cache if it does
     * not already exist in the cache store. The method will return
     * true if the item is actually added to the cache.
     * Otherwise, the method will return false.
     */
    protected function isNew(string $key): bool
    {
        return \Eshop\Models\Cart\Payout::where('reference_id', $key)->doesntExist();
    }
}