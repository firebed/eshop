<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Models\Product\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('View categories');

        return view('eshop::dashboard.category.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Category $category): View
    {
        $this->authorize('View categories');

        return view('eshop::dashboard.category.index', compact('category'));
    }
}
