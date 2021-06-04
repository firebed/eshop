<?php

namespace Database\Seeders\Live;

use App\Models\Lang\Locale;
use Illuminate\Database\Seeder;

class LocalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Locale::insert([
            ['name' => 'el'],
            ['name' => 'en']
        ]);
    }
}
