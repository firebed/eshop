<?php

namespace Eshop\Models\Location;

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

    public const PAYPAL           = 'paypal';
    public const CREDIT_CARD      = 'credit_card';
    public const WIRE_TRANSFER    = 'wire_transfer';
    public const PAY_ON_DELIVERY  = 'pay_on_delivery';
    public const PAYMENT_IN_STORE = 'pay_in_out_store';

    protected $fillable = ['name', 'show_total_on_order_form'];

    protected $casts = [
        'show_total_on_order_form' => 'boolean'
    ];

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
}
