<?php

namespace Eshop\Models\Cart;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CartStatus
 * @package App\Models\Cart
 *
 * @property integer $id
 * @property integer $cart_id
 * @property array   $metadata
 *
 * @mixin Builder
 */
class Payment extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = ['metadata', 'created_at'];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
