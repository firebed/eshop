<?php

namespace Eshop\Models\Audit;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Auditable
{
    public function audits(): MorphMany;
    
    public function toAuditableArray(): array;
}