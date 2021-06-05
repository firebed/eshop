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
            'name' => $this->faker->unique()->randomElement(['PayPal', 'Credit Card', 'Bank Transfer', 'Pay on Delivery', 'Payment in our store'])
        ];
    }
}
