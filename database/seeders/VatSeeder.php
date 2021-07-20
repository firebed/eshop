<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Product\Vat;
use Illuminate\Database\Seeder;

class VatSeeder extends Seeder
{
    public function run(): void
    {
        Vat::factory()->create(['name' => 'standard', 'regime' => .24]);
    }
}
