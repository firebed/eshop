<?php

namespace Eshop\Database\Factories\Location;

use Eshop\Models\Location\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['paypal', 'credit_card', 'wire_transfer', 'pay_on_delivery', 'pay_in_our_store'])
        ];
    }
}
