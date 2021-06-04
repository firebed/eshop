<?php

namespace Database\Seeders\Live;

use App\Models\Lang\Translation;
use App\Models\Media\Image;
use App\Models\Product\Product;
use App\Models\Product\VariantType;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use JsonException;
use RuntimeException;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $this->seedProducts();
        $this->seedProperties();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function seedProducts(): void
    {
        # Seed parents
        $products = [];
        $parents = DB::connection('live')->table('category')->get()->keyBy('id');
        $variantTypes = [];
        $variantTypeValues = [];
        $count = 0;
        foreach ($parents as $parent) {
            $products[] = $this->mapProduct($parent, $parent->party, NULL, $parent->registration, slugify($parent->name_gr));
            $variantTypes[] = ['product_id' => $count++ + 1, 'name' => 'Color'];
        }
        Product::insert($products);
        VariantType::insert($variantTypes);

        # Seed children
        $products = [];
        $temp = Product::get()->keyBy('old_id');
        $children = DB::connection('live')->table('product')->get()->keyBy('id');

        foreach ($children as $product) {
            $parent = $temp[$product->category];

//            $product->price = $parent->price;
            $product->discount = $parent->discount * 100;
            $products[] = $this->mapProduct($product, $parent->category_id, $parent->id, $parent->created_at, slugify([$parent->slug, $product->code, $product->name_gr]));
        }
        collect($products)->chunk(2500)->each(fn($chunk) => Product::insert($chunk->toArray()));

        # Translations
        $products = Product::all();
        $translations = [];
        $images = [];
        foreach ($products as $product) {
            $prd = $product->has_variants ? $parents[$product->old_id] : $children[$product->old_id];
            $translations[] = $this->mapTranslation($product->id, 'product', trim($prd->name_gr), 'name');

            if ($prd->icon_name) {
                $pref = $product->has_variants ? $prd->id : $prd->category;
                $images[] = $this->mapImage($product, $pref . '/' . $prd->icon_name, $pref . '/' . $prd->thumbnail);
            }

            if ($product->has_variants) {
                $description = $parents[$product->old_id]->description;
                if (!empty($description) && !in_array($description, ['9', '4', '1', '00'], true)) {
                    $translations[] = $this->mapTranslation($product->id, 'product', $description, 'description');
                }
            }
        }
        collect($translations)->chunk(2500)->each(fn($chunk) => Translation::insert($chunk->toArray()));
        collect($images)->chunk(2500)->each(fn($chunk) => Image::insert($chunk->toArray()));


        $variants = Product::whereNotNull('parent_id')->with('translation', 'parent.variantTypes')->get();
        foreach ($variants as $variant) {
            $option = blank($variant->name) ? $variant->sku : $variant->name;
            if ($option === null) {
                dd($variant);
            }
            $variantTypeValues[] = [
                'product_id'      => $variant->id,
                'variant_type_id' => $variant->parent->variantTypes->first()->id,
                'value'           => $option
            ];
        }
        collect($variantTypeValues)->chunk(2500)->each(function ($chunk) {
            DB::table('product_variant_type')->insert($chunk->toArray());
        });
    }

    private function mapProduct($product, $category_id, $parent_id, $created_at, $slug): array
    {
        return [
            'old_id'       => $product->id,
            'parent_id'    => $parent_id,
            'category_id'  => $category_id,
            'unit_id'      => isset($product->uom) ? $this->uom($product->uom) : 1,
            'sku'          => $product->code,
            'has_variants' => is_null($parent_id),
            'vat'          => 0.24,
            'weight'       => $product->weight ?? 0,
            'price'        => $product->price,
            'discount'     => $product->discount / 100,
            'visible'      => $product->visible,
            'available'    => $product->available ?? TRUE,
            'slug'         => $slug,
            'created_at'   => $created_at,
            'updated_at'   => $created_at,
        ];
    }

    private function mapImage($product, $src, $thumb): array
    {
        try {
            return [
                'imageable_id'   => $product->id,
                'imageable_type' => 'product',
                'collection'     => NULL,
                'disk'           => 'products',
                'src'            => $src,
                'width'          => 0,
                'height'         => 0,
                'size'           => 0,
                'conversions'    => json_encode([
                    'sm' => [
                        'src'    => $thumb,
                        'width'  => 0,
                        'height' => 0,
                        'size'   => 0,
                    ]
                ], JSON_THROW_ON_ERROR),
            ];
        } catch (JsonException $e) {
        }
    }

    private function mapTranslation($translatable_id, $translatable_type, $translation, $cluster): array
    {
        return [
            'locale'            => 'el',
            'translatable_id'   => $translatable_id,
            'translatable_type' => $translatable_type,
            'translation'       => $translation,
            'cluster'           => $cluster,
        ];
    }

    private function seedProperties(): void
    {
        $properties = [];
        $products = Product::exceptVariants()->get()->keyBy('old_id');
        $data = DB::connection('live')->table('yarns')->get();
        foreach ($data as $yarn) {
            $product = $products[$yarn->category];

            $property = $product->category_id === 1 ? 1 : 7;
            $season = empty($yarn->season) ? NULL : ($product->category_id === 1 ? $yarn->season : 6 + $yarn->season);
            $threadOffset = $product->category_id === 1 ? 0 : 6;
            $thread = empty($yarn->thread) ? NULL : (in_array($yarn->thread, ['Classic', 'Classical', 'Klasik', 'klassik', 'Κλασικός']) ? 4 : (in_array($yarn->thread, ['Fancy', 'Funcy']) ? 5 : 6) + $threadOffset);

            if ($season) {
                $properties[] = [
                    'product_id'           => $product->id,
                    'category_property_id' => $property,
                    'category_choice_id'   => $season,
                    'value'                => NULL
                ];
            }

            $properties[] = [
                'product_id'           => $product->id,
                'category_property_id' => $property + 1,
                'category_choice_id'   => NULL,
                'value'                => $yarn->composition
            ];

            $properties[] = [
                'product_id'           => $product->id,
                'category_property_id' => $property + 2,
                'category_choice_id'   => NULL,
                'value'                => $yarn->proportion
            ];

            if ($thread) {
                $properties[] = [
                    'product_id'           => $product->id,
                    'category_property_id' => $property + 3,
                    'category_choice_id'   => $thread,
                    'value'                => NULL
                ];
            }

            $properties[] = [
                'product_id'           => $product->id,
                'category_property_id' => $property + 4,
                'category_choice_id'   => NULL,
                'value'                => $yarn->needle_1 . ' - ' . $yarn->needle_2
            ];

            $properties[] = [
                'product_id'           => $product->id,
                'category_property_id' => $property + 5,
                'category_choice_id'   => NULL,
                'value'                => $yarn->needle_3 . ' - ' . $yarn->needle_4
            ];
        }

        DB::table('product_properties')->insert($properties);
    }

    /**
     * @param $val
     * @return int
     * @throws Exception
     */
    private function uom($val): int
    {
        if (in_array($val, ['ΤΕΜ', 'ΤΕΜ', '1', 'Τεμ.', '100gr', '1ΤΕΜ', '2', '2 τεμ.', '2 ΤΕΜ', '2 τεμ', '2 tem', '2τεμ', '1 τεμ', 'τεμ', 'τεμ.', 'tem', '', 'Tεμ', 'Τεμ', '400gr.', 'τετραδα', 'ζευγαρη', 'ζευγαρι'], true)) {
            return 1;
        }

        if (in_array($val, ['1μ', 'm', 'mt', 'μ', 'μετ', 'μετ.', 'ΜΕΤ', '1m', '120CM', 'μετρο', '70μ.', '50cmx80cm'])) {
            return 2;
        }

        if (in_array($val, ['ΖΕΥΓΑΡΙ', 'Ζευγαρι', 'Σετ', 'SET', 'σετ 7 τμχ.', 'σετ 10 τμχ', '137 τμχ.', '3 τμχ.', '5 τεμ.', 'Σετ 3 τεμ.', '252 τεμ.', 'σετ', '3 τεμ.'])) {
            return 3;
        }

        throw new RuntimeException("Unknown unit $val");
    }
}
