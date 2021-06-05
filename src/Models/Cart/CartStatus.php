<?php

namespace Eshop\Models\Cart;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class CartStatus
 * @package App\Models\Cart
 *
 * @property integer id
 * @property string  name
 * @property boolean notify
 * @property string  color
 * @property string  icon
 * @property integer group
 * @property string  stock_operation
 *
 * @mixin Builder
 */
class CartStatus extends Model
{
    use HasFactory;

    public const SUBMITTED = 1;
    public const APPROVED  = 2;
    public const COMPLETED = 3;
    public const SHIPPED   = 4;
    public const HELD      = 5;
    public const CANCELLED = 6;
    public const REJECTED  = 7;
    public const RETURNED  = 8;

    public const CAPTURE = 'Capture';
    public const RELEASE = 'Release';

    public $timestamps = false;

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'status_id');
    }

    public function hasCapturedStocks(): bool
    {
        return $this->id < self::CANCELLED;
    }

    public function isCapturingStocks(): bool
    {
        return $this->stock_operation === self::CAPTURE;
    }

    public function isReleasingStocks(): bool
    {
        return $this->stock_operation === self::RELEASE;
    }
}
