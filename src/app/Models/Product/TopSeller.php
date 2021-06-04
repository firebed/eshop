<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * Class TopSeller
 * @package App\Models\Product
 *
 * @property int product_id
 * @property int orders_count
 *
 * @mixin Builder
 */
class TopSeller extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
