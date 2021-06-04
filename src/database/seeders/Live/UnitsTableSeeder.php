<?php

namespace Database\Seeders\Live;

use App\Models\Product\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Unit::insert([
            ['name' => 'Piece'],
            ['name' => 'Meter'],
            ['name' => 'Set'],
            ['name' => 'Kilogram'],
        ]);
    }
}
