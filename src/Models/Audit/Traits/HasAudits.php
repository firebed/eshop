<?php

namespace Eshop\Models\Audit\Traits;

use Eshop\Models\Audit\ModelAudit;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAudits
{
    public function audits(): MorphMany
    {
        return $this->morphMany(ModelAudit::class, 'auditable');
    }
}