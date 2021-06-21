<?php

namespace Eshop\Database\Factories\Lang;

use Eshop\Models\Lang\Locale;
use Eshop\Models\Lang\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'locale'      => 'el',
            'translation' => $this->faker->words(3, true),
        ];
    }
}
