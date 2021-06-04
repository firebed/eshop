<?php

namespace Ecommerce\Database\Factories\Invoice;

use Ecommerce\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->name,
            'job'           => $this->faker->jobTitle,
            'vat_number'    => $this->faker->unique()->bothify('?? #########'),
            'tax_authority' => $this->faker->city,
        ];
    }
}
