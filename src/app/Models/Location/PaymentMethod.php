<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class CorePaymentMethod
 * @package App\Models\Cart
 *
 * @property integer $id
 * @property string  $name
 * @property ?string $icon
 * @property bool    $show_total_on_order_form
 *
 * @mixin Builder
 */
class PaymentMethod extends Model
{
    use HasFactory;

    public const PAYPAL           = 1;
    public const CREDIT_CARD      = 2;
    public const WIRE_TRANSFER    = 3;
    public const PAY_ON_DELIVERY  = 4;
    public const PAYMENT_IN_STORE = 5;

    public $timestamps = FALSE;

    public function countries(): BelongsToMany
    {
        return $this
            ->belongsToMany(Country::class)
            ->withPivot('fee', 'cart_total', 'position', 'enabled')
            ->withTimestamps();
    }

    public function isPayPal(): bool
    {
        return $this->id === self::PAYPAL;
    }

    public function isCreditCard(): bool
    {
        return $this->id === self::CREDIT_CARD;
    }

    public function isWireTransfer(): bool
    {
        return $this->id === self::WIRE_TRANSFER;
    }

    public function isPayOnDelivery(): bool
    {
        return $this->id === self::PAY_ON_DELIVERY;
    }

    public function isPaymentInStore(): bool
    {
        return $this->id === self::PAYMENT_IN_STORE;
    }
}
