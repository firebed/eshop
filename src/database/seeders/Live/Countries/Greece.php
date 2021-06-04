<?php

namespace Database\Seeders\Live\Countries;

use App\Models\Location\City;
use App\Models\Location\Province;
use App\Models\Location\Region;
use DOMDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Greece extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('peripherals')->truncate();
        DB::table('counties')->truncate();
        DB::table('cities')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->seedCounties();
    }

    private function seedPeripherals(): void
    {
        $dom = new DOMDocument();
        @$dom->loadHTMLFile('https://el.wikipedia.org/wiki/%CE%A0%CE%B5%CF%81%CE%B9%CF%86%CE%AD%CF%81%CE%B5%CE%B9%CE%B5%CF%82_%CF%84%CE%B7%CF%82_%CE%95%CE%BB%CE%BB%CE%AC%CE%B4%CE%B1%CF%82/');

        $data = $dom->getElementsByTagName('table')[0]->getElementsByTagName('a');
    }

    private function seedCounties(): void
    {
        $dom = new DOMDocument();
        @$dom->loadHTMLFile('https://www.taxidromikoskodikas.gr/');

        $counties = $dom->getElementById('nomoi_id')->getElementsByTagName('a');
        foreach ($counties as $node) {
            $province = Province::create([
                'country_id' => 1,
                'name'       => trim($node->nodeValue),
            ]);
            $this->seedCities($province, $node->getAttribute('href'));
        }
    }

    private function seedCities($province, $href): void
    {
        $dom = new DOMDocument();
        @$dom->loadHTMLFile('https://www.taxidromikoskodikas.gr/' . $href);

        $data = $dom->getElementById('perioxes_id')->getElementsByTagName('a');
        foreach ($data as $node) {
            $city = City::create([
                'country_id' => 1,
                'province_id'  => $province->id,
                'name'       => $node->nodeValue,
            ]);
            $this->seedRegions($city, $node->getAttribute('href'));
        }
    }

    private function seedRegions($city, $href): void
    {
        $dom = new DOMDocument();
        @$dom->loadHTMLFile('https://www.taxidromikoskodikas.gr/' . $href);
        $data = $dom->getElementsByTagName('tr');
        $regions = [];
        foreach ($data as $i => $tr) {
            $tds = $tr->getElementsByTagName('td');
            if ($tds->count() <= 1) {
                continue;
            }

            $regions[] = [
                'country_id' => 1,
                'province_id'  => $city->province_id,
                'city_id'    => $city->id,
                'name'       => trim($tds->item(1)->nodeValue),
                'postcode' => trim(str_replace(' ', '', $tds->item(4)->nodeValue))
            ];
        }
        Region::insert($regions);
    }
}
