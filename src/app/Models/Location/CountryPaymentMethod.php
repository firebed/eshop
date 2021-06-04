<?php


namespace Ecommerce\Models\Location;


use Ecommerce\Models\Lang\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CountShippingMethod
 * @package App\Models\Location
 */
class CountryPaymentMethod extends Pivot
{
    use HasTranslations;

    public $incrementing = TRUE;

    protected $translatable = ['description'];

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
