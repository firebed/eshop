<?php

namespace Eshop\Database\Factories\Location;

use Eshop\Models\Location\CountryPaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryPaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CountryPaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'fee'        => $this->faker->numberBetween(0, 20),
            'cart_total' => $this->faker->numberBetween(0, 100),
            'position'   => $this->faker->numberBetween(1, 20)
        ];
    }

    public function visible(): CountryPaymentMethodFactory
    {
        return $this->state(['visible' => TRUE]);
    }

    public function hidden(): CountryPaymentMethodFactory
    {
        return $this->state(['visible' => FALSE]);
    }
}
