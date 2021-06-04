<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * Class CoreShippingMethod
 * @package App\Models\Cart
 *
 * @property integer    id
 * @property string     name
 * @property ?string    tracking_url
 * @property ?string    icon
 *
 * @property Collection countries
 *
 * @mixin Builder
 */
class ShippingMethod extends Model
{
    use HasFactory;

    public const ACS = 1;

    public $timestamps = false;

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class)
            ->withPivot('fee', 'cart_total', 'weight_limit', 'weight_excess_charge', 'inaccessible_area_charge', 'position', 'enabled')
            ->withTimestamps();
    }

    public function getVoucherUrl($voucher): string
    {
        if (empty($this->tracking_url)) {
            return '#';
        }
        return Str::replaceFirst('{$tracking}', urlencode($voucher), $this->tracking_url);
    }
}
