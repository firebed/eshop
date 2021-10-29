<?php

namespace Eshop\Models\Lang;

use Eshop\Models\Lang\Traits\FullTextIndex;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Translation
 * @package App\Models\Lang
 *
 * @property string locale
 * @property string type
 * @property string translation
 * @property string keywords
 *
 * @mixin Builder
 */
class Translation extends Model
{
    use HasFactory;
    use FullTextIndex;

    public $timestamps = false;

    protected       $fillable = ['locale', 'cluster', 'translation'];
    protected array $match    = ['translation'];

    public function translatable(): MorphTo
    {
        return $this->morphTo();
    }
}
