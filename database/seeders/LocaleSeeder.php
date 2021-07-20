<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Lang\Locale;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    public function run(): void
    {
        Locale::factory()
            ->count(2)
            ->state(new Sequence(
                ['name' => 'el', 'lang' => 'Ελληνικά'],
                ['name' => 'en', 'lang' => 'Αγγλικά'],
            ))
            ->create();
    }
}
