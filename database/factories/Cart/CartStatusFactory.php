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
            'name'   => $this->faker->unique()->text(10)
        ];
    }

    public function name($name): CartStatusFactory
    {
        return $this->state(['name' => $name]);
    }

    public function notify(bool $notify = true): CartStatusFactory
    {
        return $this->state(['notify' => $notify]);
    }

    public function color(bool $color): CartStatusFactory
    {
        return $this->state(['color' => $color]);
    }

    public function icon(bool $icon): CartStatusFactory
    {
        return $this->state(['icon' => $icon]);
    }

    public function capture(): CartStatusFactory
    {
        return $this->state(['stock_operation' => CartStatus::CAPTURE]);
    }

    public function release(): CartStatusFactory
    {
        return $this->state(['stock_operation' => CartStatus::RELEASE]);
    }

    public function group(int $group): CartStatusFactory
    {
        return $this->state(['group' => $group]);
    }
}
