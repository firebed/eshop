<?php

namespace Ecommerce\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Locale
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
        if (!in_array($request->lang, ['el', 'en'])) {
            abort(404);
        }

        App::setLocale($request->lang ?? config('app.locale'));
        return $next($request);
    }
}
