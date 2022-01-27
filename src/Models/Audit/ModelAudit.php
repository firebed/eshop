<?php

namespace Eshop\Models\Audit;

use Eshop\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string user
 * @property array  payload
 * @property bool   soft_delete
 *
 * @mixin Builder
 */
class ModelAudit extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'action', 'payload', 'soft_delete'];

    protected $casts = [
        'payload'     => 'array',
        'soft_delete' => 'bool'
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
