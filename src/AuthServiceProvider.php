<?php

namespace Eshop;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}