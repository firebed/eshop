<?php

namespace Eshop\Actions\Utils;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;

class CategoryBreadcrumbs
{
    public function handle(Category $category, Product $product = NULL, Product $variant = NULL): array
    {
        $breadcrumbs = [$category];

        $parent = $category->parent;
        while ($parent) {
            $parent->load('translation');
            array_unshift($breadcrumbs, $parent);
            $parent = $parent->parent;
        }

        if ($product !== NULL) {
            $breadcrumbs[] = $product;
        }

        if ($variant !== NULL) {
            $breadcrumbs[] = $variant;
        }

        return $breadcrumbs;
    }
}