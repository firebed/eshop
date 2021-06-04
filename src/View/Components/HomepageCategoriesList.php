<?php

namespace Ecommerce\View\Components;

use Ecommerce\Models\Product\Category;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class HomepageCategoriesList extends Component
{
    public Collection $categories;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->categories = Category::with('translation')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('com::components.homepage-categories-list');
    }
}
