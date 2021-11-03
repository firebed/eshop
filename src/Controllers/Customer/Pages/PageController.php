<?php

namespace Eshop\Controllers\Customer\Pages;

use Eshop\Controllers\Customer\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __invoke(Request $request, string $locale, string $page): Renderable
    {
        return $this->page($page, $locale);
    }

    protected function page(string $filename, string $locale): Renderable
    {
        $path = "eshop::customer.pages.$filename-$locale";

        if (view()->exists($path)) {
            return view($path, ['page' => $filename]);
        }

        $defaultLocale = config('app.fallback_locale');
        if ($defaultLocale === $locale) {
            abort(404);
        }

        return $this->page($filename, $defaultLocale);
    }
}