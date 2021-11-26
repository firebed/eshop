<?php

namespace Eshop\Actions\Analytics\Traits;

use Eshop\Models\Cart\CartStatus;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait CartsQuery
{
    protected function carts(Carbon|null $from, Carbon|null $to): Builder
    {
        return DB::table('carts')
//            ->join('cart_statuses', 'cart_statuses.id', '=', 'carts.status_id')
//            ->whereIn('cart_statuses.name', CartStatus::valid())
            ->where('status_id', '<', 6)
            ->when($from !== null && $to !== null, fn($q) => $q->whereBetween('submitted_at', [$from, $to]))
            ->when($from == null && $to === null, fn($q) => $q->whereNotNull('submitted_at'));
    }
}