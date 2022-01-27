<?php

namespace Eshop\Models\Cart;

use Eshop\Models\Location\Address;
use Eshop\Models\Location\Addressable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Invoice
 * @package App\Models\Invoice
 *
 * @property int     cart_id
 * @property string  name
 * @property string  job
 * @property string  vat_number
 * @property string  tax_authority
 *
 * @property Address billingAddress
 *
 * @mixin Builder
 */
class CartInvoice extends Model
{
    use HasFactory, Addressable;

    protected $guarded = [];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function billingAddress(): MorphOne
    {
        return $this->address();
    }

    public function delete(): bool
    {
        return $this->billingAddress()->delete() && parent::delete();
    }
}
