<?php

namespace Eshop\Database\Factories\Cart;

use Eshop\Models\Cart\CartProduct;
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
        return [
            'product_id'    => ($product = Product::inRandomOrder()->first())->id,
            'quantity'      => $this->faker->numberBetween(1, 20),
            'price'         => $product->price,
            'compare_price' => $product->compare_price,
            'discount'      => $product->discount,
            'vat'           => $product->vat,
        ];
    }
}
