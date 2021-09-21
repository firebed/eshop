<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests\ProductSearchRequest;
use Eshop\Actions\Schema\WebPageSchema;
use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\Manufacturer;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class ProductOfferController extends Controller
{
    public function __invoke(string $lang, ProductSearchRequest $request, WebPageSchema $webPage): Renderable
    {
        $manufacturer_ids = collect(explode('-', $request->input('manufacturer_ids')))->filter();

        $categories = Category::whereHas('products', fn($q) => $q->exceptVariants()->onSale()->filterByPrice($request->query('min_price'), $request->query('max_price')))
            ->withCount(['products' => fn($q) => $q->exceptVariants()->onSale()->filterByPrice($request->query('min_price'), $request->query('max_price'))])
            ->with('translation')
            ->get();

        $products = Product::visible()
            ->onSale()
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

        $manufacturers = Manufacturer::whereHas('products', fn($q) => $q->exceptVariants()->onSale()->filterByPrice($request->query('min_price'), $request->query('max_price')))
            ->withCount(['products' => fn($q) => $q->exceptVariants()->onSale()->filterByPrice($request->query('min_price'), $request->query('max_price'))])
            ->get();

        return view('product-offers.index', [
            'categories'            => $categories,
            'manufacturers'         => $manufacturers,
            'products'              => $products,
            'priceRanges'           => $this->groupPriceRanges($manufacturer_ids),
            'filters'               => $request->validated(),
            'webPage'               => $webPage,
            'selectedManufacturers' => $selectedManufacturers,
        ]);
    }

    private function groupPriceRanges(Collection $manufacturer_ids): Collection
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
                'products_count' => Product::visible()
                    ->onSale()
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