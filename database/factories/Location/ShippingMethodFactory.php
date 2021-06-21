<?php

namespace Eshop\Database\Factories\Location;

use Eshop\Models\Location\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShippingMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['ACS Courier', 'Elta Courier', 'Geniki Taxydromiki', 'KTEL'])
        ];
    }
}
