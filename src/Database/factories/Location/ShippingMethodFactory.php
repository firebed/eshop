<?php

namespace Ecommerce\Database\Factories\Location;

use Ecommerce\Models\Location\ShippingMethod;
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
            'name' => $this->faker->randomElement(['ACS Courier', 'Elta Courier', 'Geniki Taxydromiki', 'KTEL'])
        ];
    }
}
