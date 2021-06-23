<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Lang\Translation;
use Eshop\Models\Product\CategoryProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryPropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryProperty::class;

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

    public function index($index): CategoryPropertyFactory
    {
        return $this->state(fn() => ['index' => $index]);
    }

    public function valueRestriction($value): CategoryPropertyFactory
    {
        return $this->state(fn() => ['value_restriction' => $value]);
    }

    public function visible(): CategoryPropertyFactory
    {
        return $this->state(fn() => ['visible' => TRUE]);
    }

    public function hidden(): CategoryPropertyFactory
    {
        return $this->state(fn() => ['visible' => FALSE]);
    }

    public function promoted(): CategoryPropertyFactory
    {
        return $this->state(fn() => ['promote' => TRUE]);
    }

    public function configure(): CategoryPropertyFactory
    {
        return $this->afterCreating(function (CategoryProperty $property) {
            $name = Translation::factory()->for($property, 'translatable')->cluster('name')->create();
            $property->slug = slugify($name->translation);
            $property->save();
        });
    }
}
