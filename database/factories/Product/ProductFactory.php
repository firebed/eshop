<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Database\Seeders\Traits\HasProducts;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\Vat;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    use HasProducts;

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
            'weight' => $this->faker->numberBetween(10, 500),
            'price'  => $this->faker->numberBetween(5, 50),
            'stock'  => $this->faker->numberBetween(0, 100),
            'vat'    => Vat::first()
        ];
    }

    public function name(string $name): ProductFactory
    {
        return $this->state([
            'name' => $name,
            'slug' => slugify($name)
        ]);
    }
}
