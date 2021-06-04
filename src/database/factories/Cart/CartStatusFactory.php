<?php

namespace Database\Factories\Cart;

use App\Models\Cart\CartStatus;
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
            'name' => $this->faker->unique()->randomElement(['Submitted', 'Approved', 'Completed', 'Shipped', 'Cancelled', 'Returned', 'Rejected'])
        ];
    }
}
