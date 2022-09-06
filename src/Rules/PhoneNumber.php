<?php

namespace Eshop\Rules;

use Eshop\Models\Location\Country;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    private Country|null $country = null;

    public function __construct(int|null $country_id)
    {
        if ($country_id !== null) {
            $this->country = Country::find($country_id);
        }
    }

    public function passes($attribute, $value): bool
    {
        if (!eshop('validate_phone_number', false) || $this->country === null) {
            return true;
        }
        
        return match ($this->country->code) {
            'GR'    => is_numeric($value) && preg_match('/^(30|0030)?69[0-9]{8}$/', $value),
            default => true
        };
    }

    public function message(): string
    {
        return "Ο αριθμός κινητού δεν είναι έγκυρος";
    }
}
