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
            'fee'               => 2.0,
            'cart_total'        => 0,
            'weight_limit'      => 4000,
            'weight_excess_fee' => 1,
            'position'          => 1
        ];
    }
}
