<?php

namespace Eshop\Models\Invoice;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property InvoiceType   type
 * @property int           client_id
 * @property PaymentMethod payment_method
 * @property string        relative_document
 * @property string        transaction_purpose
 * @property string        row
 * @property int           number
 * @property float         total_net_value
 * @property float         total_vat_amount
 * @property float         total
 * @property ?string       details
 * @property Carbon        published_at
 *
 * @property Client        client
 * @property Collection    rows
 */
class Invoice extends Model
{
    protected $fillable = [
        'type', 'client_id', 'payment_method', 'row', 'number', 'published_at',
        'relative_document', 'total_net_value', 'total_vat_amount', 'total'
    ];

    protected $casts = [
        'type'           => InvoiceType::class,
        'payment_method' => PaymentMethod::class,
        'published_at'   => 'datetime'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(InvoiceRow::class);
    }

    public function transmission(): HasOne
    {
        return $this->hasOne(InvoiceTransmission::class)->latestOfMany();
    }

    public function transmissions(): HasMany
    {
        return $this->hasMany(InvoiceTransmission::class);
    }
}