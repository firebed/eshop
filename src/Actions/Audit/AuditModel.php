<?php

namespace Eshop\Actions\Audit;

use Eshop\Models\Audit\Auditable;
use Eshop\Models\Audit\ModelAudit;

class AuditModel
{
    public function handle(Auditable $auditable, bool $soft_delete = null): void
    {
        $audit = new ModelAudit();
        $audit->user()->associate(auth()->user());
        $audit->payload = $soft_delete ? [] : $auditable->toAuditableArray();
        $audit->soft_delete = $soft_delete;
        $auditable->audits()->save($audit);
    }
}