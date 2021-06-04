<?php

namespace Database\Seeders\Live;

use App\Models\Product\Vat;
use Illuminate\Database\Seeder;

class VatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Vat::insert([
            ['name' => 'Standard',      'regime' => 0.24],
            ['name' => 'Reduced',       'regime' => 0.13],
            ['name' => 'Super reduced', 'regime' => 0.06],
            ['name' => 'Zero',          'regime' => 0.00],
        ]);
    }
}
