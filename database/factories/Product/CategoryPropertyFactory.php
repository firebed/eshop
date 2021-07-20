<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Product\CategoryChoice;
use Eshop\Models\Product\CategoryProperty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

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

    public function size(): CategoryPropertyFactory
    {
        return $this
            ->index('Multiple')
            ->valueRestriction('Multiple')
            ->state(new Sequence(['name' => 'Μέγεθος', 'slug' => 'megethos']))
            ->has(CategoryChoice::factory()
                ->count(4)
                ->state(new Sequence(
                    ['name' => 'XXL', 'slug' => 'xxl'],
                    ['name' => 'XL', 'slug' => 'xl'],
                    ['name' => 'M', 'slug' => 'm'],
                    ['name' => 'S', 'slug' => 's'],
                )), 'choices');
    }

    public function color(): CategoryPropertyFactory
    {
        return $this
            ->index('Multiple')
            ->valueRestriction('Multiple')
            ->state(new Sequence(['name' => 'Χρώμα', 'slug' => 'xrwma']))
            ->has(CategoryChoice::factory()
                ->count(5)
                ->state(new Sequence(
                    ['name' => 'Κόκκινο', 'slug' => 'kokkino'],
                    ['name' => 'Άσπρο', 'slug' => 'aspro'],
                    ['name' => 'Μπλε', 'slug' => 'mple'],
                    ['name' => 'Μαύρο', 'slug' => 'mauro'],
                    ['name' => 'Γκρι', 'slug' => 'gkri'],
                )), 'choices');
    }
}
