<?php

namespace Eshop\Database\Factories\Location;

use Eshop\Models\Location\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->country(),
            'code' => $this->faker->unique()->countryCode()
        ];
    }
}
