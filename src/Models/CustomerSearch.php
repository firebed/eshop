<?php

namespace Eshop\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string title
 * @property string description
 *
 * @mixin Builder
 */
class CustomerSearch extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = ['ip', 'search_term'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
