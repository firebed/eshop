<?php

namespace Eshop\Models\User;

use Eshop\Models\Invoice\Billable;
use Eshop\Models\Location\Addressable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    use HasFactory, Addressable, Billable;

    protected $fillable = [
        'name',
        'job',
        'vat_number',
        'tax_authority'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function delete(): bool|null
    {
        return $this->address()->delete()
            && $this->invoices()->delete()
            && parent::delete();
    }
}
