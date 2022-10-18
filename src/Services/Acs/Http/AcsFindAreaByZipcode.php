<?php

namespace Eshop\Services\Acs\Http;


class AcsFindAreaByZipcode extends AcsRequest
{
    protected string $action = 'ACS_Area_Find_By_Zip_Code';

    public function handle(string $zipcode, bool $show_only_inaccessible_areas = false): ?array
    {
        [$_, $table] = $this->request([
            "Zip_Code"                     => $zipcode,
            "Show_Only_Inaccessible_Areas" => $show_only_inaccessible_areas,
        ]);

        return $table;
    }
}