<?php

namespace Eshop\Models\Location;

use Eshop\Services\Courier\Courier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property Collection $inaccessibleAreas
 *
 * @property Collection countries
 *
 * @mixin Builder
 */
class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'tracking_url', 'icon', 'is_courier'];

    protected $casts = ['is_courier' => 'bool'];

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class)
            ->withPivot('fee', 'cart_total', 'weight_limit', 'weight_excess_fee', 'inaccessible_area_fee', 'position', 'visible')
            ->withTimestamps();
    }

    public function inaccessibleAreas(): HasMany
    {
        return $this->hasMany(InaccessibleArea::class);
    }

    public function getVoucherUrl($voucher): string
    {
        if (empty($this->tracking_url)) {
            return '#';
        }
        return Str::replaceFirst('{$tracking}', urlencode($voucher), $this->tracking_url);
    }

    public function courier(): ?Courier
    {
        return match ($this->name) {
            "SpeedEx"            => Courier::SPEEDEX,
            'Courier Center'     => Courier::COURIER_CENTER,
            "Geniki Taxydromiki" => Courier::GENIKI,
            "ACS Courier"        => Courier::ACS,
            default              => null
        };
    }
}
