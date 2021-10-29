<?php

namespace Eshop\Models\Lang;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Locale
 * @package App\Models
 *
 * @property integer id
 * @property string  name
 * @property string  lang
 *
 * @mixin Builder
 */
class Locale extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lang'];
}
