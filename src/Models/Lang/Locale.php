<?php

namespace Eshop\Models\Lang;

use Eshop\Database\Factories\Location\LocaleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Locale
 * @package App\Models
 *
 * @property integer id
 * @property string  name
 *
 * @mixin Builder
 */
class Locale extends Model
{
    use HasFactory;

    protected static function newFactory(): LocaleFactory
    {
        return LocaleFactory::new();
    }
}
