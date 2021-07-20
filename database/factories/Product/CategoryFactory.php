<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Media\Image;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\CategoryProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->text(20),
            'slug' => slugify($name)
        ];
    }

    public function file(): CategoryFactory
    {
        return $this->state(['type' => Category::FILE]);
    }

    public function folder(): CategoryFactory
    {
        return $this->state(['type' => Category::FOLDER]);
    }

    public function promoted(): CategoryFactory
    {
        return $this->state(['promote' => TRUE]);
    }

    public function name(string $name): CategoryFactory
    {
        return $this->state(['name' => $name, 'slug' => slugify($name)]);
    }

    public function ware(string $name): CategoryFactory
    {
        return $this
            ->file()
            ->name($name)
            ->promoted()
            ->has(CategoryProperty::factory()->size(), 'properties')
            ->has(CategoryProperty::factory()->color(), 'properties');
    }

    public function configure(): CategoryFactory
    {
        return $this->afterCreating(function (Category $category) {
            Image::factory()->for($category, 'imageable')->create();
        });
    }
}
