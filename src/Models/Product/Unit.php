<?php

namespace Eshop\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Unit
 * @package App\Models\Product
 *
 * @property integer id
 * @property string  name
 *
 * @mixin Builder
 */
class Unit extends Model
{
    use HasFactory;
}
