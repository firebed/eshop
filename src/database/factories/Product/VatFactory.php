<?php

namespace Database\Factories\Product;

use App\Models\Product\Vat;
use Illuminate\Database\Eloquent\Factories\Factory;

class VatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'   => $this->faker->lexify(),
            'regime' => $this->faker->unique()->randomFloat(2, 0, .24)
        ];
    }
}
