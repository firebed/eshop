<?php

namespace Eshop\Database\Factories\Product;

use Eshop\Models\Lang\Translation;
use Eshop\Models\Media\Image;
use Eshop\Models\Product\Category;
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
            'slug' => $this->faker->slug()
        ];
    }

    public function file(): CategoryFactory
    {
        return $this->state(function () {
            return [
                'type' => Category::FILE,
            ];
        });
    }

    public function folder(): CategoryFactory
    {
        return $this->state(function () {
            return [
                'type' => Category::FOLDER
            ];
        });
    }

    public function promoted(): CategoryFactory
    {
        return $this->state(fn() => ['promote' => TRUE]);
    }

    public function configure(): CategoryFactory
    {
        return $this->afterCreating(function (Category $category) {
            $name = Translation::factory()->for($category, 'translatable')->cluster('name')->create();
            $category->slug = slugify($name->translation);
            $category->save();

            Translation::factory()->for($category, 'translatable')->cluster('description')->paragraph()->create();

            Image::factory()->for($category, 'imageable')->create();
        });
    }
}
