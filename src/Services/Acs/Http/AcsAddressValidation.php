<?php

namespace Eshop\Services\Acs\Http;

use Illuminate\Support\Collection;

class AcsAddressValidation extends AcsRequest
{
    protected string $action = 'ACS_Address_Validation';

    /**
     * <ul>Address types:
     * <li>Street - Number - Postcode - Region</li>
     * <li>Street - Number - Region - Postcode</li>
     * <li>Street - Number - Postcode</li>
     * <li>Street - Number - Region</li>
     * </ul>
     *
     * @param string      $street
     * @param string|null $number
     * @param string|null $region
     * @param string      $postcode
     * @return mixed|null
     */
    public function handle(string $street, ?string $number, ?string $region, string $postcode): ?Collection
    {
        $address = implode('-', array_filter(func_get_args()));

        [$value] = $this->request([
            'Address'   => $address,
            'AddressID' => null
        ]);

        return collect($value[0]['ACSObjectOutput']) ?? null;
    }
}