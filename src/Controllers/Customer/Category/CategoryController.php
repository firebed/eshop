<?php

namespace Eshop\Controllers\Customer\Category;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Customer\Category\Traits\ValidatesCategoryUrl;
use Eshop\Models\Product\Category;
use Eshop\Requests\Customer\CustomerCategoryRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    use ValidatesCategoryUrl;

    public function __invoke(CustomerCategoryRequest $request, string $locale, Category $category): Renderable|RedirectResponse
    {
        if ($category->isFolder()) {
            $children = $category->children()
                ->visible()
                ->with('translation', 'image')
                ->with(['children' => fn($q) => $q->promoted()->visible()->with('translation')])
                ->get();

            return view('eshop::customer.category.show', compact('category', 'children'));
        }

        $filters = $request->validated();

        if (!$this->validateUrl($filters, $request->manufacturers(), $request->choices())) {
            return redirect(categoryRoute($category, $filters['m'], $filters['c'], $filters['min_price'], $filters['max_price']));
        }

        $category->load(['properties' => fn($q) => $q->whereNotNull('index')->with('translation', 'choices.translation', 'choices.property')]);
        foreach ($category->properties as $property) {
            $property->choices->loadCount(['products' => function ($q) use ($filters, $property) {
                $q->visible()
                    ->exceptVariants()
                    ->filterByManufacturers($filters['m']->pluck('id'))
                    ->filterByPropertyChoices($filters['c']->reject(fn($c) => $c->property->id === $property->id)->groupBy('property.id'))
                    ->filterByPrice($filters['min_price'], $filters['max_price']);
            }]);
        }

        $manufacturers = $category
            ->manufacturers()
            ->distinct()
            ->withCount(['products' => function (Builder $q) use ($filters, $category) {
                $q->visible()
                    ->where('category_id', $category->id)
                    ->exceptVariants()
                    ->filterByPropertyChoices($filters['c']->groupBy('property.id'))
                    ->filterByPrice($filters['min_price'], $filters['max_price']);
            }])
            ->get();

        $products = $category
            ->products()
            ->visible()
            ->exceptVariants()
            ->filterByManufacturers($filters['m']->pluck('id'))
            ->filterByPropertyChoices($filters['c']->groupBy('property.id'))
            ->filterByPrice($filters['min_price'], $filters['max_price'])
            ->with('image')
            ->with(['variants' => fn($q) => $q->visible()->with('parent', 'options', 'image')])
            ->select('products.*')
            ->joinTranslation()
            ->orderBy('name')
            ->paginate(48);

        $priceRanges = $this->groupPriceRanges($category, $filters);

        return view('eshop::customer.category.show', compact('category', 'manufacturers', 'filters', 'priceRanges', 'products'));
    }

    private function groupPriceRanges(Category $category, $filters): Collection
    {
        $max_price = $category->products()->visible()->max('price');

        $min_step = 2.5;
        $step = max(floor($max_price / 4.0), $min_step);
        $ranges = [];
        for ($i = 0; $i < 4; $i++) {
            $min = $i === 0 ? .0 : $i * $step;
            $max = $i === 3 ? .0 : ($i + 1) * $step;
            $ranges[] = collect([
                'min'            => $min,
                'max'            => $max,
                'products_count' => $category
                    ->products()
                    ->visible()
                    ->exceptVariants()
                    ->filterByManufacturers($filters['m']->pluck('id'))
                    ->filterByPropertyChoices($filters['c']->groupBy('property.id'))
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
