<?php

namespace Eshop\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string    name
 * @property string    vat_number
 * @property ?string   tax_authority
 * @property ?string   job
 * @property string    country
 * @property string    city
 * @property string    street
 * @property ?string   street_number
 * @property string    postcode
 * @property string    phone_number
 *
 * @property Invoice[] invoices
 */
class Client extends Model
{
    protected $fillable = [
        'name', 'vat_number', 'tax_authority', 'job', 
        'country', 'city', 'street', 'street_number', 'postcode', 'phone_number'
    ];
    
    public function invoice(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getAddressAttribute(): string
    {
        $address = $this->street;
        
        if (filled($this->street_number)) {
            $address .= ' ' . $this->street_number;
        }
                
        return $address . ', ' . $this->city . ' ' . $this->postcode;
    }
}