<?php

namespace Eshop\Models\Cart;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int    $id
 * @property string $reference_id
 * @property int    $orders
 * @property float  $fees
 * @property float  $total
 * @property string $attachment
 *                             
 * @mixin Builder
 */
class Payout extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['originator_id', 'originator_type', 'reference_id', 'orders', 'fees', 'total', 'attachment'];

    protected $casts = [
        'orders' => 'int',
        'fees'   => 'float',
        'total'  => 'float'
    ];

    public function originator(): MorphTo
    {
        return $this->morphTo();
    }
    
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}