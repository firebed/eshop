<?php

namespace App\Http\Controllers;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Collection;
use Eshop\Models\Slide\Slide;
use Illuminate\Contracts\Support\Renderable;

class HomepageController extends Controller
{
    public function __invoke(): Renderable
    {
        $slides = Slide::with('image')->get();
        $collections = Collection::findMany([1, 2]);

        $trending = $collections->first()
            ?->products()
            ->with('category.translation', 'image', 'translation')
            ->get()
            ->groupBy('category.name');

        $popular = $collections->skip(1)->first()
            ?->products()
            ->with('category.translation', 'image', 'translation')
            ->get();

        return view('homepage.index', [
            'slides'   => $slides,
            'trending' => $trending,
            'popular'  => $popular
        ]);
    }
}
