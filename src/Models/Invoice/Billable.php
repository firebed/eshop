<?php

namespace Eshop\Models\Invoice;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Billable
{
    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, 'billable');
    }
}