<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Product\CategoryChoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryChoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryChoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug()
        ];
    }
}
