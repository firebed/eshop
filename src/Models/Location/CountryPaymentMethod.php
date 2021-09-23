<?php


namespace Eshop\Models\Location;


use Eshop\Database\Factories\Location\CountryPaymentMethodFactory;
use Eshop\Models\Lang\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CountShippingMethod
 * @package App\Models\Location
 *
 * @mixin Builder
 */
class CountryPaymentMethod extends Pivot
{
    use HasFactory, HasTranslations;

    public $incrementing = true;

    protected array $translatable = ['description'];

    protected $casts = [
        'fee'        => 'float',
        'cart_total' => 'float',
        'position'   => 'integer',
        'visible'    => 'bool',
    ];

    protected static function newFactory(): CountryPaymentMethodFactory
    {
        return CountryPaymentMethodFactory::new();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
