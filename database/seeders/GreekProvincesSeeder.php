<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Location\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GreekProvincesSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            ['country_id' => 1, 'name' => 'Αιτωλοακαρνανίας'],
            ['country_id' => 1, 'name' => 'Αργολίδας'],
            ['country_id' => 1, 'name' => 'Αρκαδίας'],
            ['country_id' => 1, 'name' => 'Άρτας'],
            ['country_id' => 1, 'name' => 'Αττικής'],
            ['country_id' => 1, 'name' => 'Αχαΐας'],
            ['country_id' => 1, 'name' => 'Βοιωτίας'],
            ['country_id' => 1, 'name' => 'Γρεβενών'],
            ['country_id' => 1, 'name' => 'Δράμας'],
            ['country_id' => 1, 'name' => 'Δωδεκανήσου'],
            ['country_id' => 1, 'name' => 'Έβρου'],
            ['country_id' => 1, 'name' => 'Ευρυτανίας'],
            ['country_id' => 1, 'name' => 'Εύβοιας'],
            ['country_id' => 1, 'name' => 'Ζακύνθου'],
            ['country_id' => 1, 'name' => 'Ηλείας'],
            ['country_id' => 1, 'name' => 'Ημαθίας'],
            ['country_id' => 1, 'name' => 'Ηρακλείου'],
            ['country_id' => 1, 'name' => 'Θεσπρωτίας'],
            ['country_id' => 1, 'name' => 'Θεσσαλονίκης'],
            ['country_id' => 1, 'name' => 'Ιωαννίνων'],
            ['country_id' => 1, 'name' => 'Καβάλας'],
            ['country_id' => 1, 'name' => 'Καρδίτσας'],
            ['country_id' => 1, 'name' => 'Καστοριάς'],
            ['country_id' => 1, 'name' => 'Κέρκυρας'],
            ['country_id' => 1, 'name' => 'Κεφαλληνίας'],
            ['country_id' => 1, 'name' => 'Κιλκίς'],
            ['country_id' => 1, 'name' => 'Κοζάνης'],
            ['country_id' => 1, 'name' => 'Κορινθίας'],
            ['country_id' => 1, 'name' => 'Κυκλάδων'],
            ['country_id' => 1, 'name' => 'Λακωνίας'],
            ['country_id' => 1, 'name' => 'Λάρισας'],
            ['country_id' => 1, 'name' => 'Λασιθίου'],
            ['country_id' => 1, 'name' => 'Λέσβου'],
            ['country_id' => 1, 'name' => 'Λευκάδας'],
            ['country_id' => 1, 'name' => 'Μαγνησίας'],
            ['country_id' => 1, 'name' => 'Μεσσηνίας'],
            ['country_id' => 1, 'name' => 'Ξάνθης'],
            ['country_id' => 1, 'name' => 'Πέλλας'],
            ['country_id' => 1, 'name' => 'Πιερίας'],
            ['country_id' => 1, 'name' => 'Πρέβεζας'],
            ['country_id' => 1, 'name' => 'Ρεθύμνης'],
            ['country_id' => 1, 'name' => 'Ροδόπης'],
            ['country_id' => 1, 'name' => 'Σάμου'],
            ['country_id' => 1, 'name' => 'Σερρών'],
            ['country_id' => 1, 'name' => 'Τρικάλων'],
            ['country_id' => 1, 'name' => 'Φθιώτιδας'],
            ['country_id' => 1, 'name' => 'Φλώρινας'],
            ['country_id' => 1, 'name' => 'Φωκίδας'],
            ['country_id' => 1, 'name' => 'Χίου'],
            ['country_id' => 1, 'name' => 'Χαλκιδικής'],
            ['country_id' => 1, 'name' => 'Χανίων']
        ];

        DB::table('provinces')->insert($provinces);
    }
}
