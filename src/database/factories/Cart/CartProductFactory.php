<?php

namespace Database\Factories\Cart;

use App\Models\Cart\CartProduct;
use App\Models\Product\Product;
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
        $product = Product::factory()->create();
        return [
            'product_id'    => $product->id,
            'quantity'      => $this->faker->randomNumber(2),
            'price'         => $product->price,
            'compare_price' => $product->compare_price,
            'discount'      => $product->discount,
            'vat'           => $product->vat,
        ];
    }
}
