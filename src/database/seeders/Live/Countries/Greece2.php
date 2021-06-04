<?php

namespace Database\Seeders\Live\Countries;

use App\Models\Location\Country;
use App\Models\Location\Peripheral;
use DOMDocument;
use Illuminate\Database\Seeder;

class Greece2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dom = new DOMDocument();
        @$dom->loadHTMLFile('https://el.wikipedia.org/wiki/%CE%9A%CE%B1%CF%84%CE%AC%CE%BB%CE%BF%CE%B3%CE%BF%CF%82_%CF%80%CF%8C%CE%BB%CE%B5%CF%89%CE%BD_%CF%84%CE%B7%CF%82_%CE%95%CE%BB%CE%BB%CE%AC%CE%B4%CE%B1%CF%82');

        $tables = $dom->getElementsByTagName('tbody');

        $regions = [];
        $this->analyzeTable($tables->item(1), $regions); // Big Cities
        $this->analyzeTable($tables->item(2), $regions); // Small cities
        $this->analyzeTable($tables->item(3), $regions); // Regions

        $greece = Country::find(1);
        foreach ($regions as $name => $cities) {
            $region = new Peripheral(compact('name'));
            $greece->regions()->save($region);
            $region->cities()->createMany(collect($cities)->map(fn($name) => [
                'country_id' => $greece->id,
                'name'       => $name
            ]));
        }
    }

    private function analyzeTable($table, &$regions)
    {
        $rows = $table->getElementsByTagName('tr');
        foreach ($rows as $index => $row) {
            if ($index == 0) {
                continue;
            }

            $columns = $row->getElementsByTagName('td');
            $regions[trim($columns->item(4)->textContent)][] = trim($columns->item(1)->textContent);
        }
    }
}
