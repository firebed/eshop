<?php

namespace Eshop\Controllers\Dashboard\Category;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Product\Traits\WithCategoryBreadcrumbs;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Category;
use Eshop\Models\Product\CategoryProperty;
use Eshop\Requests\Dashboard\Category\CategoryPropertyDeleteRequest;
use Eshop\Requests\Dashboard\Category\CategoryPropertyRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryPropertyController extends Controller
{
    use WithNotifications,
        WithCategoryBreadcrumbs;

    public function create(Category $category): Renderable
    {
        return view('eshop::dashboard.category-property.create', [
            'category'    => $category,
            'breadcrumbs' => $this->getCategoryBreadcrumbs($category),
        ]);
    }

    public function store(CategoryPropertyRequest $request, Category $category): RedirectResponse
    {
        try {
            $property = new CategoryProperty();
            $property->fill($request->only($property->getFillable()));

            DB::transaction(function () use ($request, $category, $property) {
                $category->properties()->save($property);

                foreach ($request->input('choices') as $i => $choice) {
                    $property->choices()->updateOrCreate(['id' => $choice['id']], [
                        'name'     => $choice['name'],
                        'slug'     => slugify($choice['name'], '_'),
                        'position' => $i
                    ]);
                }
            });

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
            return redirect()->route('categories.properties.edit', $property);
        } catch (Throwable $e) {
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
            return back();
        }
    }

    public function edit(CategoryProperty $property): Renderable
    {
        $choices = $property->choices()
            ->latest('position')
            ->with('translation')
            ->get()
            ->pluck('translation.translation', 'id')
            ->map(fn($v, $k) => ['id' => $k, 'name' => $v])
            ->values()
            ->all();

        return view('eshop::dashboard.category-property.edit', [
            'category'    => $property->category,
            'breadcrumbs' => $this->getCategoryBreadcrumbs($property->category),
            'property'    => $property,
            'choices'     => $choices
        ]);
    }

    public function update(CategoryPropertyRequest $request, CategoryProperty $property): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $property) {
                $property->update($request->only($property->getFillable()));

                $choices = $property->choices()->with('translation')->get();
                $deleteIds = $choices->pluck('id')->diff(array_column($request->input('choices'), 'id'));
                if ($deleteIds->isNotEmpty()) {
                    $property->choices()->whereKey($deleteIds)->delete();
                }

                foreach ($request->input('choices') as $i => $choice) {
                    $property->choices()->updateOrCreate(['id' => $choice['id']], [
                        'name'     => $choice['name'],
                        'slug'     => slugify($choice['name'], '_'),
                        'position' => $i
                    ]);
                }
            });

            $this->showSuccessNotification(trans('eshop::notifications.saved'));
        } catch (Throwable $e) {
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
        }

        return back();
    }

    public function destroy(CategoryPropertyDeleteRequest $request, CategoryProperty $property): RedirectResponse
    {
        try {
            DB::transaction(fn() => $property->delete());

            $this->showSuccessNotification(trans('eshop::notifications.deleted'));
            return redirect()->route('categories.edit', $property->category_id);
        } catch (Throwable $e) {
            $this->showErrorNotification(trans('eshop::notifications.error'), $e->getMessage());
            return back();
        }
    }

    public function moveUp(CategoryProperty $property): RedirectResponse
    {
        $prev = CategoryProperty::orderByDesc('position')->firstWhere('position', '<', $property->position);

        if ($prev === NULL) {
            return back();
        }

        $this->swapProperties($property, $prev);

        $this->showSuccessNotification(trans('eshop::notifications.saved'));
        return back();
    }

    public function moveDown(CategoryProperty $property): RedirectResponse
    {
        $next = CategoryProperty::orderBy('position')->firstWhere('position', '>', $property->position);

        if ($next === NULL) {
            return back();
        }

        $this->showSuccessNotification(trans('eshop::notifications.saved'));
        $this->swapProperties($property, $next);

        return back();
    }

    private function swapProperties(CategoryProperty $property1, CategoryProperty $property2): void
    {
        DB::transaction(function () use ($property1, $property2) {
            $temp = $property1->position;
            $property1->update(['position' => $property2->position]);
            $property2->update(['position' => $temp]);
        });
    }
}
