<?php

namespace Ecommerce\Models\Invoice;

use App\Models\Location\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder;

/**
 * Class UserCompany
 * @package App\Models\Invoice
 *
 * @property int     user_id
 * @property string  name
 * @property string  job
 * @property string  vat_number
 * @property ?string tax_authority
 *
 * @property User    user
 *
 * @mixin Builder
 */
class Company extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
