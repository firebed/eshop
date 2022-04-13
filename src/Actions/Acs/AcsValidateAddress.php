<?php

namespace Eshop\Actions\Acs;

class AcsValidateAddress extends AcsRequest
{
    protected string $action = 'ACS_Address_Validation';

    public function handle(string $address)
    {
        [$value] = $this->request([
            'Address'   => $address,
            'AddressID' => null
        ]);

        return $value[0]['ACSObjectOutput'][0] ?? null;
    }
}