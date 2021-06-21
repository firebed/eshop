<?php

namespace Eshop\Models\Cart;

use Carbon\Carbon;
use Eshop\Database\Factories\Cart\CartFactory;
use Eshop\Models\Cart\Concerns\ImplementsOrder;
use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Models\Product\Product;
use Eshop\Models\User;
use Eshop\Repository\Contracts\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property double         shipping_fee
 * @property double         payment_fee
 * @property double         parcel_weight
 * @property string         document_type
 * @property string         email
 * @property string         ip
 * @property double         total
 * @property ?string        details
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
 * @property Invoice        invoice
 *
 * @property double         total_without_fees
 * @property double         total_fees
 * @property int            items_count
 * @property int            sum_quantity
 * @property int            products_value
 * @property Collection     shippingMethods
 * @property Collection     paymentMethods
 *
 * @method Builder submitted()
 * @method Builder abandoned()
 *
 * @mixin Builder
 */
class Cart extends Model implements Order
{
    use HasFactory, ImplementsOrder;

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

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function shippingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('cluster', 'shipping');
    }

    public function billingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('cluster', 'billing');
    }

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'billable');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(CartProduct::class)
            ->withPivot('id', 'quantity', 'price', 'compare_price', 'discount', 'vat')
            ->whereNull('cart_product.deleted_at')
            ->orderByPivot('created_at')
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |-----------------------------------------------------------------------------
    | SCOPES
    |-----------------------------------------------------------------------------
    */

    public function scopeSubmitted(Builder $query): void
    {
        $query->whereNotNull('submitted_at');
    }

    public function scopeAbandoned(Builder $query): void
    {
        $query->whereNull('submitted_at');
    }

    /*
    |-----------------------------------------------------------------------------
    | HELPERS
    |-----------------------------------------------------------------------------
    */

    public function markAsViewed(): void
    {
        $this->viewed_at = now();
    }

    public function isViewed(): bool
    {
        return $this->viewed_at !== NULL;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted_at !== NULL;
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
        if ($this->invoice) {
            $this->invoice->delete();
        }

        return $this->address()->delete() && parent::delete();
    }

    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }
}
