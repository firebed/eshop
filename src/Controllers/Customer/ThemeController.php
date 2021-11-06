<?php

namespace Eshop\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    public function __invoke(Request $request)
    {
        $key = 'theme';

        Cache::forget($key);
        
        Cache::rememberForever($key, function() use ($request) {
            return $request->input();
        });
    }
}
