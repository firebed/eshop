<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartProduct;
use Eshop\Models\Cart\CartStatus;
use Eshop\Models\Lang\Locale;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Models\Location\CountryPaymentMethod;
use Eshop\Models\Location\CountryShippingMethod;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\CategoryChoice;
use Eshop\Models\Product\CategoryProperty;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\Unit;
use Eshop\Models\Product\Vat;
use Eshop\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class EshopDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Locale::factory()->count(4)->create();

        Unit::factory()->count(4)->create();

        Vat::factory()->count(4)->create();

        ShippingMethod::factory()->count(4)->create();

        PaymentMethod::factory()->count(5)->create();

        CartStatus::factory()->count(7)->create();

        Category::factory()
            ->folder()
            ->count(5)
            ->has(Category::factory()
                ->folder()
                ->count(5)
                ->state(new Sequence(
                    ['promote' => TRUE],
                    ['promote' => FALSE],
                ))
                ->has(Category::factory()
                    ->file()
                    ->count(5)
                    ->state(new Sequence(
                        ['promote' => TRUE],
                        ['promote' => FALSE],
                    ))
                    ->has(CategoryProperty::factory()
                        ->index('Multiple')
                        ->valueRestriction('Multiple')
                        ->state(new Sequence(
                            ['promote' => TRUE],
                            ['promote' => FALSE],
                        ))
                        ->count(5)
                        ->has(CategoryChoice::factory()->count(4), 'choices'),
                        'properties')
                    ->has(Product::factory()
                        ->count(15)
                        ->vat(Vat::inRandomOrder()->first()->regime)
                    ), 'children'
                ), 'children')
            ->create();

        Country::factory()
            ->has(
                CountryShippingMethod::factory()
                    ->count(3)
                    ->for(ShippingMethod::inRandomOrder()->first())
                    ->state(new Sequence(
                        ['fee' => 2, 'cart_total' => 0],
                        ['fee' => 1, 'cart_total' => 30],
                        ['fee' => 0, 'cart_total' => 50],
                    )),
                'shippingOptions'
            )
            ->has(CountryPaymentMethod::factory()
                ->count(3)
                ->for(PaymentMethod::inRandomOrder()->first())
                ->state(new Sequence(
                    ['fee' => 2, 'cart_total' => 0],
                    ['fee' => 1, 'cart_total' => 30],
                    ['fee' => 0, 'cart_total' => 50],
                )),
                'paymentOptions'
            )
            ->count(10)
            ->create();

        Cart::factory()
            ->submitted()
            ->has(Address::factory()
                ->state(['cluster' => 'shipping'])
                ->for(Country::inRandomOrder()->first())
            )
            ->for(User::first())
            ->has(CartProduct::factory()->count(5), 'items')
            ->count(20)
            ->state(new Sequence(
                fn() => [
                    'status_id'          => CartStatus::inRandomOrder()->first()->id,
                    'payment_method_id'  => PaymentMethod::inRandomOrder()->first()->id,
                    'shipping_method_id' => ShippingMethod::inRandomOrder()->first()->id
                ],
            ))
            ->create();
    }
}
