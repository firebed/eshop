<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class AnalyticsController extends Controller
{
    public function __invoke(): Renderable
    {
        return view('eshop::dashboard.analytics.index');
    }
}
