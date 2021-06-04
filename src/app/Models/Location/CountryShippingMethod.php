<?php


namespace App\Models\Location;


use App\Models\Lang\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CountShippingMethod
 * @package App\Models\Location
 *
 * @property float fee
 * @property float cart_total
 * @property int   weight_limit
 * @property float weight_excess_fee
 * @property float inaccessible_area_fee
 * @property int   position
 * @property bool  visible
 */
class CountryShippingMethod extends Pivot
{
    use HasFactory, HasTranslations;

    public $incrementing = true;

    protected $translatable = ['description'];

//    protected $appends = ['description_for_edit'];

    protected $casts = [
        'fee'                   => 'float',
        'cart_total'            => 'float',
        'weight_limit'          => 'float',
        'weight_excess_fee'     => 'float',
        'inaccessible_area_fee' => 'float',
        'position'              => 'integer',
        'visible'               => 'bool',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

//    public function getDescriptionForEditAttribute(): string
//    {
//        return $this->description ?? '';
//    }
//
//    public function setDescriptionForEditAttribute($value): void
//    {
//        $this->description = blank($value) ? NULL : trim($value);
//    }

    public function calculateTotalFee(float $weight): float
    {
        return $this->fee + $this->calculateExcessWeightFee($weight) + $this->calculateInaccessibleAreaFee();
    }

    public function calculateExcessWeightFee($weight): float
    {
        return $weight >= $this->weight_limit
            ? ceil($weight - $this->weight_limit) * $this->weight_excess_fee
            : 0;
    }

    public function calculateInaccessibleAreaFee(): float
    {
        if ($this->id === ShippingMethod::ACS) {
            return 0;
        }

        return 0;
    }
}
