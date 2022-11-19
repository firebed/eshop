<?php

namespace Eshop\Models\Cart;

use Carbon\Carbon;
use Eshop\Models\Cart\Concerns\ImplementsOrder;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Addressable;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Models\Notification;
use Eshop\Models\Product\Product;
use Eshop\Models\User\User;
use Eshop\Repository\Contracts\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

/**
 * Class Cart
 * @package App\Models\Cart
 *
 * @property integer        id
 * @property integer        shipping_method_id
 * @property integer        payment_method_id
 * @property integer        status_id
 * @property ?string        cookie_id
 * @property ?string        payment_id
 * @property double         shipping_fee
 * @property double         payment_fee
 * @property double         parcel_weight
 * @property string         document_type
 * @property string         email
 * @property string         ip
 * @property string         channel
 * @property float          total
 * @property ?string        details
 * @property ?string        comments
 * @property ?string        voucher
 * @property ?Carbon        submitted_at
 * @property ?Carbon        viewed_at
 *
 * @property User           user
 * @property ShippingMethod shippingMethod
 * @property PaymentMethod  paymentMethod
 * @property CartStatus     status
 * @property Address        shippingAddress
 * @property Address        billingAddress
 * @property Collection     products
 * @property Collection     operators
 * @property CartInvoice    invoice
 *
 * @property float          total_without_fees
 * @property float          total_fees
 * @property int            items_count
 * @property int            sum_quantity
 * @property int            products_value
 *
 * @method Builder submitted()
 * @method Builder abandoned()
 *
 * @mixin Builder
 */
class Cart extends Model implements Order
{
    use HasFactory, Addressable, ImplementsOrder;

    protected $guarded = [];

    protected $casts = [
        'submitted_at'  => 'datetime',
        'viewed_at'     => 'datetime',
        'shipping_fee'  => 'float',
        'payment_fee'   => 'float',
        'parcel_weight' => 'int',
        'total'         => 'float'
    ];

    /*
    |-----------------------------------------------------------------------------
    | RELATIONS
    |-----------------------------------------------------------------------------
    */

    public function events(): HasMany
    {
        return $this->hasMany(CartEvent::class);
    }
    
    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cart_operator')->withPivot('viewed_at');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(CartStatus::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->payment !== null;
    }

    public function shippingAddress(): MorphOne
    {
        return $this->address('shipping');
    }

    public function billingAddress(): MorphOne
    {
        return $this->address('billing');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(CartInvoice::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(CartProduct::class)
            ->withPivot('id', 'quantity', 'price', 'compare_price', 'discount', 'vat', 'pinned')
            ->whereNull('cart_product.deleted_at')
            ->orderByPivot('created_at')
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }

    public function voucher(): HasOne
    {
        return $this->hasOne(Voucher::class)->latestOfMany('created_at');
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    /*
    |-----------------------------------------------------------------------------
    | SCOPES
    |-----------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSubmitted(Builder $query): void
    {
        $query->whereNotNull('submitted_at');
    }

    /*
    |-----------------------------------------------------------------------------
    | HELPERS
    |-----------------------------------------------------------------------------
    */

    public function scopeAbandoned(Builder $query): void
    {
        $query->whereNull('submitted_at');
    }

    public function markAsViewed(): void
    {
        $this->viewed_at = now();
    }

    public function isViewed(): bool
    {
        return $this->viewed_at !== null;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted_at !== null;
    }

    public function isDocumentInvoice(): bool
    {
        return $this->document_type === DocumentType::INVOICE;
    }

    public function isDocumentReceipt(): bool
    {
        return $this->document_type === DocumentType::RECEIPT;
    }

    public function getDocumentNameAttribute(): string
    {
        return $this->isDocumentInvoice() ? "Invoice" : "Receipt";
    }

    public function delete(): bool|null
    {
        $this->addresses()->delete();
        return parent::delete();
    }

    protected static function booted(): void
    {
        static::addGlobalScope('safe', function (Builder $builder) {
            if (panicking()) {
                $builder->whereNull('submitted_at')->orWhereDate('submitted_at', '>', today()->subMonth());
            }
        });
    }
}
