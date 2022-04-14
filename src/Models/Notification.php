<?php

namespace Eshop\Models;

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
class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'metadata', 'viewed_at', 'created_at'];

    public const UPDATED_AT = null;
    
    protected $casts = ['metadata' => 'json'];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
