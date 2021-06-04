<?php

namespace Ecommerce\Database\Factories\Location;

use Ecommerce\Models\Location\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'phone'      => $this->faker->phoneNumber,
            'province'   => $this->faker->state,
            'city'       => $this->faker->city,
            'street'     => $this->faker->streetName,
            'street_no'  => $this->faker->numerify(),
            'postcode'   => $this->faker->postcode,
        ];
    }
}
