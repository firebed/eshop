<?php

namespace Eshop\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int             invoice_id
 * @property int             code
 * @property UnitMeasurement unit
 * @property string          description
 * @property float           quantity
 * @property float           price
 * @property float           discount
 * @property float           net_value
 * @property float           vat_percent
 * @property int             position
 *
 * @property Invoice         invoice
 */
class InvoiceRow extends Model
{
    protected $fillable = [
        'invoice_id', 'code', 'description', 'unit',
        'quantity', 'price', 'discount', 'vat_percent', 'position'
    ];

    protected $casts = [
        'unit'        => UnitMeasurement::class,
        'quantity'    => 'float',
        'price'       => 'float',
        'discount'    => 'float',
        'vat_percent' => 'float',
        'position'    => 'integer'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}