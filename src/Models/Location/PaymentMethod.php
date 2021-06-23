<?php

namespace Eshop\Models\Location;

use Eshop\Database\Factories\Location\PaymentMethodFactory;
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

    public const PAYPAL           = 'PayPal';
    public const CREDIT_CARD      = 'Credit Card';
    public const WIRE_TRANSFER    = 'Bank Transfer';
    public const PAY_ON_DELIVERY  = 'Pay on Delivery';
    public const PAYMENT_IN_STORE = 'Payment in our store';

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
        return $this->name === self::PAYPAL;
    }

    public function isCreditCard(): bool
    {
        return $this->name === self::CREDIT_CARD;
    }

    public function isWireTransfer(): bool
    {
        return $this->name === self::WIRE_TRANSFER;
    }

    public function isPayOnDelivery(): bool
    {
        return $this->name === self::PAY_ON_DELIVERY;
    }

    public function isPaymentInStore(): bool
    {
        return $this->name === self::PAYMENT_IN_STORE;
    }

    protected static function newFactory(): PaymentMethodFactory
    {
        return PaymentMethodFactory::new();
    }
}
