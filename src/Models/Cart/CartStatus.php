<?php

namespace Eshop\Models\Cart;

use Eshop\Database\Factories\Cart\CartStatusFactory;
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

    public const SUBMITTED = 'Submitted';
    public const APPROVED  = 'Approved';
    public const COMPLETED = 'Completed';
    public const SHIPPED   = 'Shipped';
    public const HELD      = 'Held';
    public const CANCELLED = 'Cancelled';
    public const REJECTED  = 'Rejected';
    public const RETURNED  = 'Returned';

    public const CAPTURE = 'Capture';
    public const RELEASE = 'Release';

    public $timestamps = FALSE;

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'status_id');
    }

    public function hasCapturedStocks(): bool
    {
        return in_array($this->name, [
            self::SUBMITTED,
            self::APPROVED,
            self::COMPLETED,
            self::SHIPPED,
            self::HELD
        ]);
    }

    public function isCapturingStocks(): bool
    {
        return $this->stock_operation === self::CAPTURE;
    }

    public function isReleasingStocks(): bool
    {
        return $this->stock_operation === self::RELEASE;
    }

    protected static function newFactory(): CartStatusFactory
    {
        return CartStatusFactory::new();
    }
}
