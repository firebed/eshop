<?php

namespace Eshop\Database\Factories\Cart;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'shipping_fee'  => $this->faker->randomFloat(2, 0, 10),
            'payment_fee'   => $this->faker->randomFloat(2, 0, 10),
            'document_type' => $this->faker->randomElement([DocumentType::RECEIPT, DocumentType::INVOICE]),
            'details'       => $this->faker->paragraph(),
            'ip'            => $this->faker->ipv4(),
            'email'         => $this->faker->safeEmail(),
        ];
    }

    public function configure(): CartFactory
    {
        return $this->afterCreating(function (Cart $cart) {
            $cart->updateTotalWeight();
//            $cart->updateFees();
            $cart->updateTotal();
            $cart->save();
        });
    }

    public function submitted(): CartFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'submitted_at' => $this->faker->dateTimeThisYear,
            ];
        });
    }
}