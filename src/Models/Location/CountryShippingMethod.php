<?php


namespace Eshop\Models\Location;


use Eshop\Database\Factories\Location\CountryShippingMethodFactory;
use Eshop\Models\Lang\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
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
 *
 * @mixin Builder
 */
class CountryShippingMethod extends Pivot
{
    use HasFactory, HasTranslations;

    public $incrementing = true;

    protected array $translatable = ['description'];

    protected $casts = [
        'fee'                   => 'float',
        'cart_total'            => 'float',
        'weight_limit'          => 'float',
        'weight_excess_fee'     => 'float',
        'inaccessible_area_fee' => 'float',
        'position'              => 'integer',
        'visible'               => 'bool',
    ];

    protected static function newFactory(): CountryShippingMethodFactory
    {
        return CountryShippingMethodFactory::new();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
