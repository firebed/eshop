<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Product\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'type' => 'File',
            'slug' => $this->faker->slug()
        ];
    }
}
