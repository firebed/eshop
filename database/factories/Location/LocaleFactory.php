<?php

namespace Eshop\Database\Factories\Location;

use Eshop\Models\Lang\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Locale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['el', 'en', 'tr', 'es']),
            'lang' => $this->faker->unique()->randomElement(['Ελληνικά', 'English', 'Türkçe', 'Spanish'])
        ];
    }
}
