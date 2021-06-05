<?php

namespace Eshop\Models\Location;

use Eshop\Models\Lang\Traits\FullTextIndex;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Address
 * @package App\Models
 *
 * @property ?int    related_id
 * @property ?string cluster
 * @property integer country_id
 * @property string  first_name
 * @property string  last_name
 * @property string  phone
 * @property string  province
 * @property string  city
 * @property string  street
 * @property string  street_no
 * @property string  floor
 * @property string  postcode
 *
 * @property Country country
 *
 * @mixin Builder
 */
class Address extends Model
{
    use HasFactory;
    use FullTextIndex;

    protected $guarded = [];

    protected $match = ['first_name', 'last_name', 'phone', 'postcode'];

    protected $casts = [
        'related_id' => 'integer'
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getToAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isLocalCountry(): bool
    {
        return strcasecmp($this->country->code, config('app.country')) === 0;
    }

    public function isRelated(): bool
    {
        return $this->related_id !== NULL;
    }

    public function getFullStreetAttribute(): string
    {
        return collect([$this->street, $this->street_no])->filter()->implode(' ');
    }

    public function getCityOrCountryAttribute(): string
    {
        return filled($this->city) && filled($this->postcode) ? $this->postcode . ', ' . $this->city : $this->country->name;
    }
}
