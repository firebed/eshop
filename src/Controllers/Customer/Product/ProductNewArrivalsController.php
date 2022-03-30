<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Eshop\Requests\Customer\ProductNewArrivalRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class ProductNewArrivalsController extends Controller
{
    public function __invoke(string $lang, ProductNewArrivalRequest $request): Renderable
    {
        $manufacturer_ids = collect(explode('-', $request->input('manufacturer_ids')))->filter();

        $categories = Category::visible()
            ->whereHas('products', fn($q) => $q->visible()->exceptVariants()->recent()->filterByPrice($request->query('min_price'), $request->query('max_price')))
            ->withCount(['products' => fn($q) => $q->visible()->exceptVariants()->recent()->filterByPrice($request->query('min_price'), $request->query('max_price'))])
            ->with('translation')
            ->get();

        $products = Product::visible()
            ->visible()
            ->recent()
            ->exceptVariants()
            ->filterByManufacturers($manufacturer_ids)
            ->filterByPrice($request->query('min_price'), $request->query('max_price'))
            ->with('category', 'image', 'translations')
            ->with(['variants' => fn($q) => $q->visible()->with('parent.translation', 'options', 'image')])
            ->select('products.*')
            ->joinTranslation()
            ->orderBy('name')
            ->paginate(48);

        (new \Illuminate\Database\Eloquent\Collection($products->pluck('variants')->collapse()->pluck('options')->collapse()->pluck('pivot')))->load('translation');

        $selectedManufacturers = collect();
        if (count($manufacturer_ids) > 0) {
            $selectedManufacturers = Manufacturer::findMany($manufacturer_ids);
        }

        $manufacturers = Manufacturer::whereHas('products', fn($q) => $q->visible()->exceptVariants()->recent()->filterByPrice($request->query('min_price'), $request->query('max_price')))
            ->withCount(['products' => fn($q) => $q->visible()->exceptVariants()->recent()->filterByPrice($request->query('min_price'), $request->query('max_price'))])
            ->get();

        return $this->view('product-new-arrivals.index', [
            'categories'            => $categories,
            'manufacturers'         => $manufacturers,
            'products'              => $products,
            'priceRanges'           => $this->groupPriceRanges($manufacturer_ids),
            'filters'               => $request->validated(),
            'selectedManufacturers' => $selectedManufacturers,
        ]);
    }

    private function groupPriceRanges(Collection $manufacturer_ids): Collection
    {
        $max_price = Product::visible()->recent()->max('price');

        $min_step = 2.5;
        $step = max(floor($max_price / 4.0), $min_step);
        $ranges = [];
        for ($i = 0; $i < 4; $i++) {
            $min = $i === 0 ? .0 : $i * $step;
            $max = $i === 3 ? .0 : ($i + 1) * $step;
            $ranges[] = collect([
                'min'            => $min,
                'max'            => $max,
                'products_count' => Product::visible()
                    ->recent()
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
