<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VariantType
 * @package App\Models\Product
 *
 * @property Product product
 * @property string  name
 *
 * @mixin Builder
 */
class VariantType extends Model
{
    use HasFactory;

    public    $timestamps = FALSE;
    protected $guarded    = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
