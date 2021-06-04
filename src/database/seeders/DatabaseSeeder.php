<?php

namespace Database\Seeders;

use App\Models\Cart\Cart;
use App\Models\Cart\CartProduct;
use App\Models\Cart\CartStatus;
use App\Models\Location\Address;
use App\Models\Location\Country;
use App\Models\Location\PaymentMethod;
use App\Models\Location\ShippingMethod;
use App\Models\Product\Vat;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->create();

        ShippingMethod::factory()->count(4)->create();

        Vat::factory()->count(4)->create();

        CartStatus::factory()->count(7)->create();

        $countryWithShippingAndPaymentMethods = Country::factory()
            ->hasAttached(ShippingMethod::factory(), [
                'fee'               => 2.0,
                'cart_total'        => 0,
            ])
            ->hasAttached(ShippingMethod::factory(), [
                'fee'               => 1.0,
                'cart_total'        => 30,
            ])
            ->hasAttached(ShippingMethod::factory(), [
                'fee'               => 0.0,
                'cart_total'        => 50,
            ])
            ->hasAttached(PaymentMethod::factory(), [
                'fee'        => 2,
                'cart_total' => 0
            ])
            ->create();

        // Cart with products, shipping address, shipping and payment methods
        Cart::factory()
            ->has(Address::factory()
                ->state(['cluster' => 'shipping'])
                ->for($countryWithShippingAndPaymentMethods)
            )
            ->has(CartProduct::factory()->count(5), 'items')
            ->create();

        // Empty cart with shipping address plus shipping and payment methods
        Cart::factory()
            ->has(Address::factory()
                ->state(['cluster' => 'shipping'])
                ->for($countryWithShippingAndPaymentMethods)
            )
            ->create();

        // Cart with products + shipping address and empty shipping/payment options
        Cart::factory()
            ->has(CartProduct::factory()->count(5), 'items')
            ->has(Address::factory()->state(['cluster' => 'shipping'])->for(Country::factory()))
            ->create();

        // Cart with products and no shipping address
        Cart::factory()
            ->has(CartProduct::factory()->count(5), 'items')
            ->create();
    }
}
