<?php

namespace Eshop\Models\Cart;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $cart_id
 * @property int    $shipping_method_id
 * @property string $number
 * @property bool   $auto_generated
 *
 * @mixin Builder
 */
class Voucher extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['shipping_method_id', 'number'];

    protected $casts = ['auto_generated' => 'bool'];
}