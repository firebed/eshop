<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Models\Product\Category;
use Eshop\Controllers\Controller;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('eshop::dashboard.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return View
     */
    public function show(Category $category): View
    {
        return view('eshop::dashboard.category.index', compact('category'));
    }
}
