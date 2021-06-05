<?php

namespace Eshop;

use Eshop\Livewire\Customer\Checkout\CartButton;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        Livewire::component('customer.checkout.cart-button', CartButton::class);
    }
}