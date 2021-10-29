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

    public const SUBMITTED = 'submitted';
    public const APPROVED  = 'approved';
    public const COMPLETED = 'completed';
    public const SHIPPED   = 'shipped';
    public const HELD      = 'held';
    public const CANCELLED = 'cancelled';
    public const REJECTED  = 'rejected';
    public const RETURNED  = 'returned';

    public const CAPTURE = 'Capture';
    public const RELEASE = 'Release';

    public $timestamps = false;

    protected $casts = ['notify' => 'bool'];

    public static function calculable(): array
    {
        return [self::SUBMITTED, self::APPROVED, self::COMPLETED, self::SHIPPED, self::HELD];
    }

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
}
