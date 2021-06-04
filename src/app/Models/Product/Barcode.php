<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Barcode
 * @package App\Models\Product
 *
 * @property integer id
 * @property integer product_id
 * @property string  barcode
 * @property double  quantity
 *
 * @mixin Builder
 */
class Barcode extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'barcode', 'quantity'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
