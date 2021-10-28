<?php

namespace Eshop\Controllers\Dashboard;

use Eshop\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SidebarController extends Controller
{
    public function __invoke()
    {
        $key = 'dashboard-sidebar-collapsed.' . auth()->id();

        $previous = Cache::get($key, false);
        
        Cache::forget($key);        
        Cache::rememberForever($key, fn() => !$previous);
    }
}
