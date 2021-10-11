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
     * @param Category     $category
     * @param Product|null $product
     */
    public function __construct(Category $category, Product $product = null)
    {
        $parent = $category->parent;
        while ($parent) {
            $parent->load('translation');
            array_unshift($this->items, [
                'name' => $parent->name,
                'url'  => categoryRoute($parent)
            ]);
            $parent = $parent->parent;
        }

        array_unshift($this->items, [
            'name' => __('Home'),
            'url'  => route('home', app()->getLocale())
        ]);

        $this->items[] = [
            'name' => $category->name,
            'url'  => categoryRoute($category)
        ];

        if ($product !== null) {
            if ($product->isVariant()) {
                $this->items[] = [
                    'name' => $product->parent->trademark,
                    'url'  => productRoute($product->parent, $category)
                ];

                $this->items[] = [
                    'name' => $product->option_values,
                    'url'  => productRoute($product, $category)
                ];
            } else {
                $this->items[] = [
                    'name' => $product->trademark,
                    'url'  => productRoute($product, $category)
                ];
            }
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
