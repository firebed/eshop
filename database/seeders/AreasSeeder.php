<?php

namespace Eshop\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AreasSeeder extends Seeder
{
    public function run(): void
    {
        DB::disableQueryLog();
        Schema::disableForeignKeyConstraints();
        DB::table('inaccessible_areas')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->seedAreasFromFile('greece.csv', 1);
//        $this->seedAreasFromFile('cyprus.csv', 3);
    }

    private function seedAreasFromFile(string $filename, int $country_id): void
    {
        $country = array_map('str_getcsv', file(__DIR__ . "/areas/$filename"));
        $headers = $country[0];
        array_shift($headers);
        array_unshift($headers, 'Περιοχή');

        array_shift($country);

        array_walk($country, static function (&$area) use ($headers) {
            $area = array_combine($headers, $area);
        });

        $csv = array_map(static fn($area) => [
            'shipping_method_id' => 1, // ACS
            'country_id'         => $country_id,
            'region'             => trim($area['Περιοχή']),
            'type'               => empty($type = trim($area['Είδος'])) ? null : $type,
            'courier_store'      => trim($area['Κατάστημα']),
            'courier_county'     => trim($area['Νομός']),
            'courier_address'    => trim($area['Διεύθυνση']),
            'courier_phone'      => trim($area['Τηλέφωνα']),
            'postcode'           => trim($area['TK']),
        ], $country);

        $chunks = array_chunk($csv, 2000, true);
        foreach ($chunks as $chunk) {
            DB::table('inaccessible_areas')->insert($chunk);
        }
    }
}
