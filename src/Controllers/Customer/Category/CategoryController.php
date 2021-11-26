<?php

namespace Eshop\Controllers\Customer\Category;

use Eshop\Controllers\Customer\Category\Traits\ValidatesCategoryUrl;
use Eshop\Controllers\Customer\Controller;
use Eshop\Models\Product\Category;
use Eshop\Requests\Customer\CategoryRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    use ValidatesCategoryUrl;

    public function __invoke(CategoryRequest $request, string $locale, Category $category): Renderable|RedirectResponse
    {
        if (!$category->visible) {
            abort(404);
        }

        if ($category->isFolder()) {
            $children = $category->children()
                ->visible()
                ->with('translation', 'image')
                ->with(['children' => fn($q) => $q->promoted()->visible()->with('translation')])
                ->get();

            return $this->view('category.show', [
                'category' => $category,
                'children' => $children
            ]);
        }

        $filters = $request->validated();

        if (!$this->validateUrl($filters, $request->manufacturers(), $request->choices())) {
            return redirect(categoryRoute($category, $filters['m'], $filters['c'], $filters['min_price'], $filters['max_price']));
        }

        $category->load(['properties' => fn($q) => $q->with('translation', 'choices.translation', 'choices.property')]);
        foreach ($category->properties as $property) {
            $property->choices->loadCount(['products' => function ($q) use ($request, $filters, $property) {
                $q->visible()
                    ->exceptVariants()
                    ->when($request->isManufacturerFilteringEnabled(), fn($b) => $b->filterByManufacturers($filters['m']->pluck('id')))
                    ->filterByPropertyChoices($filters['c']->reject(fn($c) => $c->property->id === $property->id)->groupBy('property.id'))
                    ->filterByPrice($filters['min_price'], $filters['max_price']);
            }]);
        }

        if ($request->isManufacturerFilteringEnabled()) {
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
        }

        $order = 'name';
        $direction = 'asc';
        if ($request->query('sort') === 'price') {
            $order = 'price';
        } elseif ($request->query('sort') === 'price-desc') {
            $order = 'price';
            $direction = 'desc';
        }

        $products = $category
            ->products()
            ->visible()
            ->exceptVariants()
            ->when($request->isManufacturerFilteringEnabled(), fn($b) => $b->filterByManufacturers($filters['m']->pluck('id')))
            ->filterByPropertyChoices($filters['c']->groupBy('property.id'))
            ->filterByPrice($filters['min_price'], $filters['max_price'])
            ->with('translations') // We need this for different languages
            ->with('image', 'category')
//            ->with(['choices' => fn($q) => $q->with('property.translation', 'translation')])
            ->with(['variants' => fn($q) => $q->visible()->with('translation', 'parent', 'options', 'image')])
            ->select('products.*')
            ->joinTranslation()
            ->orderBy($order, $direction)
            ->paginate(48);

        $priceRanges = $this->groupPriceRanges($category, $filters);

        return $this->view('category.show', [
            'category'      => $category,
            'manufacturers' => $manufacturers ?? collect(),
            'filters'       => $filters,
            'priceRanges'   => $priceRanges,
            'products'      => $products
        ]);
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
