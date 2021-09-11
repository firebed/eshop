<?php

namespace Eshop\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    shipping_method_id
 * @property int    country_id
 * @property string region
 * @property string type
 * @property string courier_store
 * @property string courier_county
 * @property string courier_address
 * @property string courier_phone
 * @property string courier_postcode
 */
class InaccessibleArea extends Model
{
    public $timestamps = FALSE;

    protected $fillable = [
        'shipping_method_id',
        'country_id',
        'region',
        'type',
        'courier_store',
        'courier_county',
        'courier_address',
        'courier_phone',
        'postcode'
    ];

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
