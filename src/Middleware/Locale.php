<?php

namespace Eshop\Middleware;

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
        $available_locales = array_keys(eshop('locales', []));
        
        if (!empty($available_locales) && !in_array($request->lang, $available_locales)) {
            abort(404);
        }

        App::setLocale($request->lang ?? config('app.locale'));
        return $next($request);
    }
}
