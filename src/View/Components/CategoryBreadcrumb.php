<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Category;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

class CategoryBreadcrumb extends Component
{
    public array $items = [];

    /**
     * Create a new component instance.
     *
     * @param Category $category
     */
    public function __construct(Category $category, Product $product = NULL, Product $variant = NULL)
    {
        $this->items[] = [
            'name'  => __('Home'),
            'url' => route('home', app()->getLocale())
        ];

        $parent = $category->parent;
        while ($parent) {
            $parent->load('translation');
            array_unshift($this->items, $parent);
            $parent = $parent->parent;
        }

        $this->items[] = [
            'name' => $category->name,
            'url'  => categoryRoute($category)
        ];

        if ($product !== NULL) {
            $this->items[] = [
                'name' => $product->name,
                'url'  => productRoute($product, $category)
            ];
        }

        if ($variant !== NULL) {
            $this->items[] = [
                'name' => $variant->option_values,
                'url'  => variantRoute($variant, $product, $category)
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('eshop::components.category-breadcrumb');
    }
}
