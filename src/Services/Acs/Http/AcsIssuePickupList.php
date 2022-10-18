<?php

namespace Eshop\Services\Acs\Http;


class AcsIssuePickupList extends AcsRequest
{
    protected string $action = 'ACS_Issue_Pickup_List';

    /**
     * <ul>Address types:
     * <li>Street - Number - Postcode - Region</li>
     * <li>Street - Number - Region - Postcode</li>
     * <li>Street - Number - Postcode</li>
     * <li>Street - Number - Region</li>
     * </ul>
     *
     * @param string $address
     * @return array|int
     */
    public function handle(string $address): array|int
    {
        return $this->request([
            'Address'   => $address,
            'AddressID' => null
        ]);
    }
}