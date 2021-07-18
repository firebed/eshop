<?php

namespace Eshop\Models\Product;

use Eshop\Database\Factories\Location\AddressFactory;
use Eshop\Database\Factories\Product\VatFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vat
 * @package App\Models\Product
 *
 * @property integer id
 * @property string  name
 * @property double  regime
 *
 * @mixin Builder
 */
class Vat extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): VatFactory
    {
        return VatFactory::new();
    }
}
