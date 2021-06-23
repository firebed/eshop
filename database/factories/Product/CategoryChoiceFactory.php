<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Lang\Translation;
use Eshop\Models\Product\CategoryChoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryChoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryChoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug()
        ];
    }

    public function configure(): CategoryChoiceFactory
    {
        return $this->afterCreating(function (CategoryChoice $property) {
            $name = Translation::factory()->for($property, 'translatable')->cluster('name')->create();
            $property->slug = slugify($name->translation);
            $property->save();
        });
    }
}
