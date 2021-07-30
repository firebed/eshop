<?php


namespace Eshop\Controllers\Dashboard\Product\Traits;


use Eshop\Models\Product\Category;
use Illuminate\Database\Eloquent\Collection;

trait WithCategoryBreadcrumbs
{
    protected function getCategoryBreadcrumbs(Category $category): array
    {
        $models = collect([]);

        $parent = $category->parent;
        while ($parent) {
            $models[] = $parent;

            $parent = $parent->parent;
        }

        $models = $models->reverse();

        $breadcrumbs = [];
        if ($models->isNotEmpty()) {
            Collection::make($models)->load('translation');

            foreach ($models as $model) {
                $breadcrumbs[$model->id] = $model->name;
            }
        }

        return $breadcrumbs;
    }
}