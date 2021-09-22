<?php

namespace App\Http\Controllers\Pages;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __invoke(Request $request, string $locale, string $page): Renderable
    {
        return $this->view($page, $locale);
    }

    protected function view(string $filename, string $locale): Renderable
    {
        $path = "pages.$filename-$locale";

        if (view()->exists($path)) {
            return view($path, ['page' => $filename]);
        }

        $defaultLocale = config('app.fallback_locale');
        if ($defaultLocale === $locale) {
            abort(404);
        }

        return $this->view($filename, $defaultLocale);
    }
}