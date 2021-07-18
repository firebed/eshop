<?php

namespace Eshop\Models\Seo;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string title
 * @property string description
 *
 * @mixin Builder
 */
class Seo extends Model
{
    use HasFactory;

    protected $table = 'seo';

    protected $fillable = ['locale', 'title', 'description'];

    public function seo(): MorphTo
    {
        return $this->morphTo();
    }
}
