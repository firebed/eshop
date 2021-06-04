<?php

namespace Database\Factories\Product;

use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\Vat;
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
        $vat = Vat::inRandomOrder()->first();
        return [
            'category_id'   => Category::factory(),
            'vat'           => $vat->regime,
            'weight'        => $this->faker->numberBetween(10, 500),
            'price'         => $this->faker->numberBetween(1, 50),
            'compare_price' => 0,
            'discount'      => $this->faker->numberBetween(1, 100) / 100,
            'stock'         => $this->faker->numberBetween(0, 100),
            'slug'          => $this->faker->slug()
        ];
    }
}
