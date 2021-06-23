<?php

namespace Eshop\Database\Factories\Cart;

use Eshop\Models\Cart\CartStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Submitted', 'Held', 'Approved', 'Completed', 'Shipped', 'Cancelled', 'Returned', 'Rejected'])
        ];
    }
}
