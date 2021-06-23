<?php

namespace Eshop\Database\Factories\Location;

use Eshop\Models\Location\CountryShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryShippingMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CountryShippingMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'fee'               => $this->faker->numberBetween(0, 20),
            'cart_total'        => $this->faker->numberBetween(0, 100),
            'weight_limit'      => $this->faker->numberBetween(0, 10000),
            'weight_excess_fee' => $this->faker->numberBetween(1, 10),
            'position'          => $this->faker->numberBetween(1, 20)
        ];
    }
}
