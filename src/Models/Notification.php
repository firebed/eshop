<?php

namespace Eshop\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $text
 * @property array  $metadata
 * @property string $body
 * @property Carbon $viewed_at
 * @property Carbon $created_at
 *
 * @mixin Builder
 */
class Notification extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = ['text', 'metadata', 'body', 'viewed_at'];

    protected $casts = [
        'metadata'    => 'array',
    ];
}
