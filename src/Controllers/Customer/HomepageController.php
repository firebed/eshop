<?php

namespace Eshop\Controllers\Customer;

use Eshop\Actions\Schema\OrganizationSchema;
use Eshop\Actions\Schema\WebPageSchema;
use Eshop\Actions\Schema\WebSiteSchema;
use Eshop\Controllers\Controller;
use Eshop\Models\Product\Product;
use Eshop\Models\Slide\Slide;
use Illuminate\Contracts\Support\Renderable;

class HomepageController extends Controller
{
    public function __invoke(WebSiteSchema $webSite, WebPageSchema $webPage, OrganizationSchema $organization): Renderable
    {
        $slides = Slide::with('image')->get();
        $trending = Product::exceptVariants()->inRandomOrder()->with('category', 'image', 'translation')->take(10)->get();
        $popular = Product::exceptVariants()->inRandomOrder()->with('category', 'image', 'translation')->take(10)->get();

        return view('eshop::customer.homepage.index', [
            'slides'       => $slides,
            'trending'     => $trending,
            'popular'      => $popular,
            'webSite'      => $webSite->handle(),
            'webPage'      => $webPage,
            'organization' => $organization->handle()
        ]);
    }
}