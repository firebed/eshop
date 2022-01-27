<?php

namespace Eshop\Database\Factories\Invoice;

use Eshop\Models\User\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->name(),
            'job'           => $this->faker->jobTitle(),
            'vat_number'    => $this->faker->unique()->bothify('?? #########'),
            'tax_authority' => $this->faker->city(),
        ];
    }
}
