<?php

namespace Eshop\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    invoice_id
 * @property string uid
 * @property string mark
 * @property string cancelled_by_mark
 *                                   
 * @property Invoice invoice
 */
class InvoiceTransmission extends Model
{
    protected $fillable = [
        'uid', 'mark', 'cancelled_by_mark'
    ];
    
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function isCancelled(): bool
    {
        return filled($this->cancelled_by_mark);
    }
}