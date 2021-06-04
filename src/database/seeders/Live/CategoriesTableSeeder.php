<?php

namespace Database\Seeders\Live;

use App\Models\Lang\Translation;
use App\Models\Product\Category;
use App\Models\Product\CategoryChoice;
use App\Models\Product\CategoryProperty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->seedCategories();
        $this->seedProperties();
    }

    private function seedCategories(): void
    {
        $data = DB::connection('live')->table('party')->get();
        $categories = [];
        $translations = [];
        foreach ($data as $i => $category) {
            $categories[] = [
                'type'       => Category::FILE,
                'visible'    => TRUE,
                'slug'       => slugify($category->name),
                'created_at' => now()
            ];

            $translations[] = [
                'locale'            => 'el',
                'cluster'           => 'name',
                'translatable_id'   => $i + 1,
                'translatable_type' => 'category',
                'translation'       => $category->name,
            ];
        }

        Category::insert($categories);
        Translation::insert($translations);
    }

    private function seedProperties(): void
    {
        $properties = [];
        $choices = [];
        $translations = [];
        foreach ([1, 3] as $i => $category_id) {
            $properties[] = $this->mapProperty($category_id, 'Multiple', 'Multiple', slugify('Εποχή'), 1, FALSE);
            $properties[] = $this->mapProperty($category_id, 'None', 'None', slugify('Σύνθεση'), 2, FALSE);
            $properties[] = $this->mapProperty($category_id, 'None', 'None', slugify('Αναλογία'), 3, FALSE);
            $properties[] = $this->mapProperty($category_id, 'Simple', 'Simple', slugify('Τύπος'), 4, FALSE);
            $properties[] = $this->mapProperty($category_id, 'None', 'None', slugify('Βελόνες'), 5, TRUE);
            $properties[] = $this->mapProperty($category_id, 'None', 'None', slugify('Βελονάκια'), 6, TRUE);

            $choices[] = ['category_property_id' => $i * 6 + 1, 'slug' => slugify('Καλοκαιρινό'), 'position' => 1, 'created_at' => now(), 'updated_at' => now()];      // Summer
            $choices[] = ['category_property_id' => $i * 6 + 1, 'slug' => slugify('Χειμερινό'), 'position' => 2, 'created_at' => now(), 'updated_at' => now()];        // Winter
            $choices[] = ['category_property_id' => $i * 6 + 1, 'slug' => slugify('4 Εποχών', '_'), 'position' => 3, 'created_at' => now(), 'updated_at' => now()];    // 4 Season
            $choices[] = ['category_property_id' => $i * 6 + 4, 'slug' => slugify('Classical'), 'position' => 1, 'created_at' => now(), 'updated_at' => now()];        // Classical
            $choices[] = ['category_property_id' => $i * 6 + 4, 'slug' => slugify('Fancy'), 'position' => 2, 'created_at' => now(), 'updated_at' => now()];            // Fancy
            $choices[] = ['category_property_id' => $i * 6 + 4, 'slug' => slugify('Bebe'), 'position' => 3, 'created_at' => now(), 'updated_at' => now()];             // Bebe

            foreach (['Εποχή', 'Σύνθεση', 'Μήκος', 'Τύπος', 'Βελόνες', 'Βελονάκια'] as $j => $property) {
                $translations[] = [
                    'translatable_id'   => $i * 6 + $j + 1,
                    'translatable_type' => 'category_property',
                    'locale'            => 'el',
                    'cluster'           => 'name',
                    'translation'       => $property
                ];
            }

            foreach (['Καλοκαιρινό', 'Χειμερινό', '4 Εποχών'] as $j => $property) {
                $translations[] = [
                    'translatable_id'   => $i * 6 + $j + 1,
                    'translatable_type' => 'category_choice',
                    'locale'            => 'el',
                    'cluster'           => 'name',
                    'translation'       => $property
                ];
            }

            foreach (['Classical', 'Fancy', 'Bebe'] as $j => $property) {
                $translations[] = [
                    'translatable_id'   => $i * 6 + 3 + $j + 1,
                    'translatable_type' => 'category_choice',
                    'locale'            => 'el',
                    'cluster'           => 'name',
                    'translation'       => $property
                ];
            }
        }

        CategoryProperty::insert($properties);
        CategoryChoice::insert($choices);
        Translation::insert($translations);
    }

    private function mapProperty($category_id, $value_restriction, $index, $slug, $position, $show_label): array
    {
        return [
            'category_id'       => $category_id,
            'value_restriction' => $value_restriction,
            'index'             => $index,
            'slug'              => $slug,
            'position'          => $position,
            'show_caption'      => $show_label,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }
}
