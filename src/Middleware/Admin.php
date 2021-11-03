<?php

namespace Eshop\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!(auth()->user()?->can('View dashboard') || auth()->user()?->hasRole('Super Admin'))) {
            abort(403);
        }

        return $next($request);
    }
}
