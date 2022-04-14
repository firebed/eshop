<?php

namespace Eshop\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $text
 * @property array  $metadata
 * @property Carbon $viewed_at
 * @property Carbon $created_at
 *
 * @mixin Builder
 */
class Notification extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;
    
    protected $fillable = ['text', 'metadata', 'viewed_at', 'created_at'];
    
    protected $casts = ['metadata' => 'json'];
}
