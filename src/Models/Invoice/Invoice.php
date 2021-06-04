<?php

namespace Ecommerce\Models\Invoice;

use Ecommerce\Models\Location\Address;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Invoice
 * @package App\Models\Invoice
 *
 * @property Address billingAddress
 *
 * @mixin Builder
 */
class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    public function billingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function delete()
    {
        if (parent::delete()) {
            $this->billingAddress()->delete();
        }
    }
}
