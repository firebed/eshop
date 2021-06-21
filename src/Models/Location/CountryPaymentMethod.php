<?php


namespace Eshop\Models\Location;


use Eshop\Models\Lang\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
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
    use HasTranslations;

    public $incrementing = TRUE;

    protected array $translatable = ['description'];

//    protected $appends = ['description_for_edit'];

    protected $casts = [
        'fee'        => 'float',
        'cart_total' => 'float',
        'position'   => 'integer',
        'visible'    => 'bool',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
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

    public function calculateTotalFee(): float
    {
        return $this->fee;
    }
}
