<?php

namespace Eshop\Models\Cart;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int                 $id
 * @property string              $pickup_id
 *
 * @property Collection<Voucher> $vouchers
 *
 * @method Builder notCancelled()
 *
 * @mixin Builder
 */
class Pickup extends Model
{
    protected $fillable = ['pickup_id'];
    
    public function vouchers(): BelongsToMany
    {
        return $this->belongsToMany(Voucher::class);
    }

    public function scopeNotCancelled(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at');
    }
}