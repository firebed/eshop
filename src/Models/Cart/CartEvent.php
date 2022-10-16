<?php

namespace Eshop\Models\Cart;

use Eshop\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string  $cart_id
 * @property boolean $user_id
 * @property string  $type
 * @property string  $action
 * @property string  $title
 * @property array   $details
 *
 * @mixin Builder
 */
class CartEvent extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = ['details' => 'array'];

    public const INFO    = 'info';
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const ERROR   = 'error';

    public const CHECKOUT_PRODUCTS = 'checkout-products';
    public const CHECKOUT_DETAILS  = 'checkout-details';
    public const CHECKOUT_PAYMENT  = 'checkout-payment';

    public const ORDER_VIEWED          = 'order-viewed';
    public const ORDER_SUBMITTED       = 'order-submitted';
    public const ORDER_SUBMITTED_EMAIL = 'order-submitted-email';
    public const ORDER_APPROVED        = 'order-approved';
    public const ORDER_APPROVED_EMAIL  = 'order-approved-email';
    public const ORDER_COMPLETED       = 'order-completed';
    public const ORDER_COMPLETED_EMAIL = 'order-completed-email';
    public const ORDER_SHIPPED         = 'order-shipped';
    public const ORDER_SHIPPED_EMAIL   = 'order-shipped-email';
    public const ORDER_HELD            = 'order-held';
    public const ORDER_HELD_EMAIL      = 'order-held-email';
    public const ORDER_CANCELLED       = 'order-cancelled';
    public const ORDER_CANCELLED_EMAIL = 'order-cancelled-email';
    public const ORDER_REJECTED        = 'order-rejected';
    public const ORDER_REJECTED_EMAIL  = 'order-rejected-email';
    public const ORDER_RETURNED        = 'order-returned';
    public const ORDER_RETURNED_EMAIL  = 'order-returned-email';
    public const ORDER_PAID            = 'order-paid';

    public const VOUCHER_UPDATED = 'voucher-updated';

    public const RESUME_AUTH   = 'resume-auth';
    public const RESUME_COOKIE = 'resume-cookie';

    public const ABANDONMENT_EMAIL_1 = 'abandonment-email-1';
    public const ABANDONMENT_EMAIL_2 = 'abandonment-email-2';
    public const ABANDONMENT_EMAIL_3 = 'abandonment-email-3';
    public const ABANDONMENT_EMAIL_1_VIEWED = 'abandonment-email-1-viewed';
    public const ABANDONMENT_EMAIL_2_VIEWED = 'abandonment-email-2-viewed';
    public const ABANDONMENT_EMAIL_3_VIEWED = 'abandonment-email-3-viewed';
    public const RESUME_ABANDONED_1  = 'resume-abandoned-1';
    public const RESUME_ABANDONED_2  = 'resume-abandoned-2';
    public const RESUME_ABANDONED_3  = 'resume-abandoned-3';

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function info($cartId, $action, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'type'    => self::INFO,
            'action'  => $action,
            'title'   => __("eshop::cart.events.$action"),
            'details' => $details
        ]);
    }

    public static function success($cartId, $action, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'type'    => self::SUCCESS,
            'action'  => $action,
            'title'   => __("eshop::cart.events.$action"),
            'details' => $details
        ]);
    }

    public static function error($cartId, $action, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'type'    => self::ERROR,
            'action'  => $action,
            'title'   => __("eshop::cart.events.$action"),
            'details' => $details
        ]);
    }

    public static function warning($cartId, $action, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'type'    => self::WARNING,
            'action'  => $action,
            'title'   => __("eshop::cart.events.$action"),
            'details' => $details
        ]);
    }

    public static function getCheckoutProducts($cartId): self
    {
        return self::firstOrCreate([
            'cart_id' => $cartId,
            'type'    => self::INFO,
            'action'  => CartEvent::CHECKOUT_PRODUCTS,
        ], [
            'user_id' => auth()->id(),
            'title'   => __("eshop::cart.events.get_checkout_products")
        ]);
    }

    public static function getCheckoutDetails($cartId): self
    {
        return self::firstOrCreate([
            'cart_id' => $cartId,
            'type'    => self::INFO,
            'action'  => CartEvent::CHECKOUT_DETAILS,
        ], [
            'user_id' => auth()->id(),
            'title'   => __("eshop::cart.events.get_checkout_details")
        ]);
    }

    public static function setCheckoutDetails($cartId, $type = self::SUCCESS, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'type'    => $type,
            'action'  => CartEvent::CHECKOUT_DETAILS,
            'title'   => __("eshop::cart.events.set_checkout_details"),
            'details' => $details
        ]);
    }

    public static function getCheckoutPayment($cartId): void
    {
        self::firstOrCreate([
            'cart_id' => $cartId,
            'type'    => self::INFO,
            'action'  => CartEvent::CHECKOUT_PAYMENT,
        ], [
            'user_id' => auth()->id(),
            'title'   => __("eshop::cart.events.get_checkout_payment")
        ]);
    }

    public static function setCheckoutPayment($cartId, $type = self::SUCCESS, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'type'    => $type,
            'action'  => CartEvent::CHECKOUT_PAYMENT,
            'title'   => __("eshop::cart.events.set_checkout_payment"),
            'details' => $details
        ]);
    }

    public static function paypal($cartId, $type, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'type'    => $type,
            'action'  => CartEvent::CHECKOUT_PAYMENT,
            'title'   => __("eshop::cart.events.paypal_checkout"),
            'details' => $details
        ]);
    }

    public static function checkoutInsufficientQuantity($cartId): self
    {
        return self::create([
            'cart_id' => $cartId,
            'action'  => CartEvent::CHECKOUT_PRODUCTS,
            'type'    => self::ERROR,
            'user_id' => auth()->id(),
            'title'   => __("eshop::cart.events.checkout_insufficient_quantity"),
        ]);
    }

    public static function checkoutTotalUpdated($cartId): self
    {
        return self::create([
            'cart_id' => $cartId,
            'action'  => CartEvent::CHECKOUT_PRODUCTS,
            'type'    => self::WARNING,
            'user_id' => auth()->id(),
            'title'   => __("eshop::cart.events.checkout_total_updated"),
        ]);
    }

    public static function orderPaid($cartId, $details = null): self
    {
        return self::create([
            'cart_id' => $cartId,
            'action'  => CartEvent::ORDER_PAID,
            'type'    => self::INFO,
            'user_id' => auth()->id(),
            'title'   => __("eshop::cart.events.order_paid"),
            'details' => $details
        ]);
    }

    public static function orderViewed($cartId): self
    {
        return self::create([
            'cart_id' => $cartId,
            'action'  => CartEvent::ORDER_VIEWED,
            'type'    => self::INFO,
            'user_id' => auth()->id(),
            'title'   => __("eshop::cart.events.order_viewed"),
        ]);
    }

    public static function resumeAbandoned(int $cartId, string $action): self
    {
        return self::firstOrCreate([
            'cart_id' => $cartId,
            'action'  => $action,
        ], [
            'user_id' => auth()->id(),
            'type'    => self::INFO,
            'title'   => __("eshop::cart.events.$action"),
        ]);
    }

    public static function openEmail(int $cartId, string $action, int|null $user_id): self
    {
        return self::firstOrCreate([
            'cart_id' => $cartId,
            'action'  => $action,
        ], [
            'user_id' => $user_id,
            'type'    => self::INFO,
            'title'   => __("eshop::cart.events.$action"),
        ]);
    }

    public static function resumeAuth(int $cartId): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => auth()->id(),
            'action'  => self::RESUME_AUTH,
            'type'    => self::INFO,
        ]);
    }

    public static function resumeCookie(int $cartId): self
    {
        return self::create([
            'cart_id' => $cartId,
            'user_id' => null,
            'action'  => self::RESUME_COOKIE,
            'type'    => self::INFO,
            'title'   => 'Συνέχιση καλαθιού από cookie'
        ]);
    }
}
