<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Product\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()
            ->folder()
            ->name('Ανδρικά Ρούχα')
            ->has(Category::factory()->ware('Ανδρικά T-Shirts'), 'children')
            ->has(Category::factory()->ware('Ανδρικές Μπλούζες'), 'children')
            ->has(Category::factory()->ware('Ανδρικά Παντελόνια'), 'children')
            ->create();

        Category::factory()
            ->folder()
            ->name('Γυναικεία Ρούχα')
            ->has(Category::factory()->ware('Γυναικεία T-Shirts'), 'children')
            ->has(Category::factory()->ware('Γυναικείες Μπλούζες'), 'children')
            ->has(Category::factory()->ware('Γυναικεία Παντελόνια'), 'children')
            ->create();
    }
}
