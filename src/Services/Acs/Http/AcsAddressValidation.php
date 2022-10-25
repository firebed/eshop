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

        return collect($value[0]['ACSObjectOutput'] ?? [])
            ->map(function ($station) {
                if ($station['Resolved_As_Inaccesible_Area_With_Cost']) {
                    $type = 'ΔΠ';
                } elseif ($station['Resolved_As_Inaccesible_Area_WithOut_Cost']) {
                    $type = 'ΔΧ';
                }

                return [
                    'id'   => $station['Resolved_Station_ID'],
                    'name' => $station['Resolved_Station_Descr'],
                    'type' => $type ?? null,
                ];
            });
    }
}