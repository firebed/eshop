<?php

namespace Eshop\Models\Location;

use Eshop\Models\Cart\Payout;
use Eshop\Services\Courier\Couriers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    public function payouts(): MorphMany
    {
        return $this->morphMany(Payout::class, 'originator');
    }

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

    public function iconSrc(): string|null
    {
        return match ($this->name) {
            "SpeedEx"           => "SpeedEx.png",
            'Courier Center'    => "courier-center.jpeg",
            "GenikiTaxydromiki" => "geniki.jpg",
            "ACS Courier"       => "ACS.png",
            "ELTA"              => "elta.png",
            "ELTA Courier"      => "elta-courier.png",
            default             => null
        };
    }

    public function courier(): Couriers
    {
        return match ($this->name) {
            "SpeedEx"           => Couriers::SPEEDEX,
            'Courier Center'    => Couriers::COURIER_CENTER,
            "GenikiTaxydromiki" => Couriers::GENIKI,
            "ACS Courier"       => Couriers::ACS,
            "ELTA Courier"      => Couriers::ELTA_COURIER,
            default             => null
        };
    }
}
