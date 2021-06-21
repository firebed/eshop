<?php

namespace Eshop\Database\Factories\Cart;

use Eshop\Models\Cart\CartProduct;
use Eshop\Models\Lang\Translation;
use Eshop\Models\Media\Image;
use Eshop\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $product = Product::factory()
            ->has(Translation::factory()->state(['cluster' => 'name']))
            ->has(Image::factory())
            ->create();

        return [
            'product_id'    => $product->id,
            'quantity'      => $this->faker->numberBetween(1, 20),
            'price'         => $product->price,
            'compare_price' => $product->compare_price,
            'discount'      => $product->discount,
            'vat'           => $product->vat,
        ];
    }
}
