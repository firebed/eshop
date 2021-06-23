<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Lang\Translation;
use Eshop\Models\Media\Image;
use Eshop\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'weight'        => $this->faker->numberBetween(10, 500),
            'price'         => $price = $this->faker->numberBetween(5, 50),
            'compare_price' => $this->faker->numberBetween(1, $price),
            'discount'      => $this->faker->numberBetween(1, 100) / 100,
            'stock'         => $this->faker->numberBetween(0, 100),
            'slug'          => $this->faker->slug()
        ];
    }

    public function configure(): ProductFactory
    {
        return $this->afterCreating(function (Product $product) {
            $name = Translation::factory()->for($product, 'translatable')->cluster('name')->create();
            $product->slug = slugify($name->translation);
            $product->save();

            Translation::factory()->for($product, 'translatable')->cluster('description')->paragraph()->create();

            Image::factory()->for($product, 'imageable')->create();
        });
    }

    public function vat(float $vat): ProductFactory
    {
        return $this->state(fn() => ['vat' => $vat]);
    }
}
