<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Product\Unit;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::factory()
            ->count(4)
            ->state(new Sequence(
                ['name' => 'piece'],
                ['name' => 'set'],
                ['name' => 'meter'],
                ['name' => 'weight'],
            ))
            ->create();
    }
}
