<?php

namespace Eshop\Database\Factories\Media;

use Eshop\Models\Media\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'type' => Image::TYPE_URL,
            'src'  => $this->faker->imageUrl()
        ];
    }

    public function url($url): ImageFactory
    {
        return $this->state([
            'type' => Image::TYPE_URL,
            'src' => $url
        ]);
    }
}
