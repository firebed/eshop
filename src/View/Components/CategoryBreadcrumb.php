<?php

namespace Eshop\View\Components;

use Eshop\Models\Product\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

class CategoryBreadcrumb extends Component
{
    public Category $leaf;
    public array $categories;

    /**
     * Create a new component instance.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->leaf = $category;

        $this->categories = [];
        $parent = $category->parent;
        while ($parent) {
            $parent->load('translation');
            array_unshift($this->categories, $parent);
            $parent = $parent->parent;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Renderable
     */
    public function render(): Renderable
    {
        return view('com::components.category-breadcrumb');
    }
}
