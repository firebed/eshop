<?php

namespace Eshop;

use Eshop\Models\Cart\Cart;
use Eshop\Policies\CartPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Cart::class => CartPolicy::class,
    ];

    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        $this->registerPolicies();
    }
}