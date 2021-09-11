<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Actions\HighlightText;
use Eshop\Actions\Schema\WebPageSchema;
use Eshop\Actions\ProductsSearch;
use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Eshop\Requests\Customer\ProductSearchRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductSearchController extends Controller
{
    public function index(string $lang, ProductSearchRequest $request, ProductsSearch $search, WebPageSchema $webPage): Renderable
    {
        $search_term = $request->input('search_term', '');

        $manufacturer_ids = collect(explode('-', $request->input('manufacturer_ids')))->filter();

        $categories = Category::whereHas('products', fn($q) => $q->filterByPrice($request->query('min_price'), $request->query('max_price'))->whereHas('translations', fn($c) => $c->matchAgainst($search_term)->where('cluster', 'name')))
            ->withCount(['products' => fn($q) => $q->filterByPrice($request->query('min_price'), $request->query('max_price'))->whereHas('translations', fn($c) => $c->matchAgainst($search_term)->where('cluster', 'name'))])
            ->with('translation')
            ->get();

        $products = $search
            ->handle($search_term)
            ->exceptVariants()
            ->filterByManufacturers($manufacturer_ids)
            ->filterByPrice($request->query('min_price'), $request->query('max_price'))
            ->with('category', 'image')
            ->with(['variants' => fn($q) => $q->visible()->with('parent.translation', 'options', 'image')])
            ->select('products.*')
            ->joinTranslation()
            ->orderBy('name')
            ->paginate(48);

        $selectedManufacturers = collect();
        if (count($manufacturer_ids) > 0) {
            $selectedManufacturers = Manufacturer::findMany($selectedManufacturers);
        }

        $manufacturers = Manufacturer::whereHas('products', fn($q) => $q->whereHas('translations', fn($c) => $c->matchAgainst($search_term)->where('cluster', 'name'))->filterByPrice($request->query('min_price'), $request->query('max_price')))->get();

        return view('eshop::customer.product-search.index', [
            'search_term'           => $search_term,
            'categories'            => $categories,
            'manufacturers'         => $manufacturers,
            'products'              => $products,
            'priceRanges'           => $this->groupPriceRanges($search, $manufacturer_ids, $search_term),
            'filters'               => $request->validated(),
            'webPage'               => $webPage,
            'selectedManufacturers' => $selectedManufacturers,
        ]);
    }

    public function ajax(string $lang, Request $request, ProductsSearch $search, HighlightText $highlight): JsonResponse
    {
        if ($request->isNotFilled('search_term')) {
            return response()->json([]);
        }

        $q = $request->input('search_term', '');

        $products = $search->handle($q)->take(10)->get();

        $results = [];
        foreach ($products as $product) {
            $text = $highlight->handle($product->name, $q);

            $results[] = [
                'text' => $text,
                'href' => route('customer.products.show', [$lang, $product->category->slug, $product->slug, 'search_term' => $q])
            ];
        }

//        if (Auth::check()) {
//            auth()->user()->searches()->save(new CustomerSearch([
//                'ip' => $request->ip(),
//                'search_term' => $q
//            ]));
//        } else {
//            session()->push('customer_searches', $q);
//        }

        return response()->json($results);
    }

    private function groupPriceRanges(ProductsSearch $search, Collection $manufacturer_ids, $search_term): Collection
    {
        $max_price = Product::visible()->max('price');

        $min_step = 2.5;
        $step = max(floor($max_price / 4.0), $min_step);
        $ranges = [];
        for ($i = 0; $i < 4; $i++) {
            $min = $i === 0 ? .0 : $i * $step;
            $max = $i === 3 ? .0 : ($i + 1) * $step;
            $ranges[] = collect([
                'min'            => $min,
                'max'            => $max,
                'products_count' => $search->handle($search_term)
                    ->exceptVariants()
                    ->filterByManufacturers($manufacturer_ids)
                    ->filterByPrice($min, $max)
                    ->count()
            ]);

            if (($i + 1) * $step >= $max_price) {
                break;
            }
        }

        return collect($ranges);
    }
}