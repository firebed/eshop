<?php

namespace Eshop\Models\Location;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Addressable
{
    public function address(string $cluster = null): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')
            ->when($cluster, fn($q) => $q->where('cluster', $cluster));
    }

    public function addresses(string $cluster = null): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable')
            ->when($cluster, fn($q) => $q->where('cluster', $cluster));
    }
}