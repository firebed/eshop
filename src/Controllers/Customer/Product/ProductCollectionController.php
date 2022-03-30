<?php

namespace Eshop\Controllers\Customer\Product;

use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Collection as BaseCollection;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Eshop\Requests\Customer\ProductCollectionRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class ProductCollectionController extends Controller
{
    public function __invoke(string $lang, ProductCollectionRequest $request, BaseCollection $collection): Renderable
    {
        $manufacturer_ids = collect(explode('-', $request->input('manufacturer_ids')))->filter();

        $categories = Category::visible()
            ->whereHas('products', fn($q) => $this->applyConstraints($q, $collection, $request))
            ->withCount(['products' => fn($q) => $this->applyConstraints($q, $collection, $request)])
            ->with('translation')
            ->get();

        $products = Product::visible()
            ->visible()
            ->exceptVariants()
            ->filterByManufacturers($manufacturer_ids)
            ->filterByPrice($request->query('min_price'), $request->query('max_price'))
            ->whereHas('collections', fn($b) => $b->where('collection_id', $collection->id))
            ->with('category', 'image', 'translations')
            ->with(['variants' => fn($q) => $q->visible()->with('parent.translation', 'options', 'image')])
            ->select('products.*')
            ->joinTranslation()
            ->latest()
            ->paginate(48);
 
        (new \Illuminate\Database\Eloquent\Collection($products->pluck('variants')->collapse()->pluck('options')->collapse()->pluck('pivot')))->load('translation');

        $selectedManufacturers = collect();
        if (count($manufacturer_ids) > 0) {
            $selectedManufacturers = Manufacturer::findMany($manufacturer_ids);
        }

        $manufacturers = Manufacturer::whereHas('products', fn($q) => $this->applyConstraints($q, $collection, $request))
            ->withCount(['products' => fn($q) => $this->applyConstraints($q, $collection, $request)])
            ->get();

        return $this->view('product-collection.index', [
            'collection'            => $collection,
            'categories'            => $categories,
            'manufacturers'         => $manufacturers,
            'products'              => $products,
            'priceRanges'           => $this->groupPriceRanges($manufacturer_ids, $collection),
            'filters'               => $request->validated(),
            'selectedManufacturers' => $selectedManufacturers,
        ]);
    }

    private function applyConstraints($query, $collection, $request)
    {
        return $query->visible()
            ->exceptVariants()
            ->whereHas('collections', fn($b) => $b->where('collection_id', $collection->id))
            ->filterByPrice($request->query('min_price'), $request->query('max_price'));
    }

    private function groupPriceRanges(Collection $manufacturer_ids, $collection): Collection
    {
        $max_price = Product::visible()
            ->whereHas('collections', fn($b) => $b->where('collection_id', $collection->id))
            ->max('price');

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
                    ->whereHas('collections', fn($b) => $b->where('collection_id', $collection->id))
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
