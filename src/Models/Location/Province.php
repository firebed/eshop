<?php

namespace Ecommerce\Models\Location;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Province
 * @package App\Models\Location
 *
 * @property integer id
 * @property string  name
 * @property boolean shippable
 *
 * @property Country country
 *
 * @mixin Builder
 */
class Province extends Model
{
    use HasFactory;

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
