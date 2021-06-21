<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Product\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Piece', 'Meter', 'Set', 'Weight']),
        ];
    }
}
